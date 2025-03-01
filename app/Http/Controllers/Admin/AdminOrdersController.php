<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Artwork;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingCondition;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class AdminOrdersController extends Controller
{
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());

            $orders = Order::with([
                'user.profile',
                'payment'
            ])->latest()->paginate(10);

            return view('admin.orders.index', compact('orders'));
        } catch (Throwable $e) {
            Log::error("❌ Error loading orders list: " . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', __('Error loading orders list.'));
        }
    }

    public function show(Request $request, Order $order)
    {
        try {
            isAllowed($request->user());

            $order->load([
                'user.profile',
                'billingAddress',
                'shippingAddress',
                'shippingCondition',
                'items.artwork'
            ]);

            return view('admin.orders.show', [
                'order' => $order,
                'billingAddress' => $order->billingAddress,
                'shippingAddress' => $order->shippingAddress ?? $order->billingAddress,
                'shippingConditions' => ShippingCondition::all(),
            ]);
        } catch (Throwable $e) {
            Log::error("❌ Error loading order details (Order ID: {$order->id}): " . $e->getMessage());
            return redirect()->route('admin.orders.index')->with('error', __('Error loading order details.'));
        }
    }

    public function create(Request $request)
    {
        try {
            isAllowed($request->user());

            return view('admin.orders.create', [
                'users' => User::with('profile.addresses')->get(),
                'artworks' => Artwork::select('id', 'name', 'initial_price as price', 'image')->get(),
                'shippingConditions' => ShippingCondition::all(),
            ]);
        } catch (Throwable $e) {
            Log::error("❌ Error loading order creation form: " . $e->getMessage());
            return redirect()->route('admin.orders.index')->with('error', __('Error loading order creation form.'));
        }
    }

    public function store(Request $request)
    {
        try {
            isAllowed($request->user());

            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'billing_address_id' => 'required|exists:addresses,id',
                'shipping_address_id' => 'nullable|exists:addresses,id',
                'artworks' => 'required|array|min:1',
                'artworks.*' => 'exists:artworks,id',
                'quantities' => 'required|array',
                'total_price' => 'required|numeric|min:0',
                'shipping_condition_id' => 'required|exists:shipping_conditions,id',
                'recipient_name' => 'nullable|string',
                'recipient_email' => 'nullable|email',
                'recipient_phone' => 'nullable|string',
            ]);

            $order = Order::create([
                'user_id' => $validated['user_id'],
                'billing_address_id' => $validated['billing_address_id'],
                'shipping_address_id' => $validated['shipping_address_id'] ?? null,
                'total' => $validated['total_price'],
                'stripe_session_id' => 'manual_' . uniqid(),
                'shipping_condition_id' => $validated['shipping_condition_id'],
                'recipient_name' => $validated['recipient_name'] ?? 'Unknown Recipient',
                'recipient_email' => $validated['recipient_email'] ?? User::find($validated['user_id'])->email,
                'recipient_phone' => $validated['recipient_phone'] ?? null,
            ]);

            return redirect()->route('admin.orders.index')->with('success', __('Order created successfully.'));
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            Log::error("❌ Error creating order: " . $e->getMessage());
            return back()->with('error', __('Failed to create order.'));
        }
    }

    public function update(Request $request, Order $order)
    {
        try {
            Log::info("🔄 Starting order update process for Order ID: {$order->id}");
            Log::info("📝 Request data: " . json_encode($request->all()));

            isAllowed($request->user());
            Log::info("✅ Authorization check passed for user: {$request->user()->id}");

            // Get user profile ID from the user
            $userProfileId = null;
            if ($request->has('user_id')) {
                $user = User::find($request->input('user_id'));
                if ($user && $user->profile) {
                    $userProfileId = $user->profile->id;
                    Log::info("✅ Found user profile ID: {$userProfileId} for user: {$user->id}");
                } else {
                    Log::warning("⚠️ Could not find user profile for user ID: {$request->input('user_id')}");
                }
            }

            // First, update or create the billing address if it exists in the request
            if ($request->has('billing_address')) {
                Log::info("🔄 Processing billing address");

                // Fetch the current billing address or create a new one
                if ($order->billing_address_id) {
                    $billingAddress = Address::find($order->billing_address_id);
                    if ($billingAddress) {
                        $billingAddress->update([
                            'address' => $request->input('billing_address'),
                            'city' => $request->input('billing_city'),
                            'state' => $request->input('billing_state'),
                            'country' => $request->input('billing_country'),
                            'zipcode' => $request->input('billing_zipcode'),
                            // Only update user_profile_id if we have it and it's not already set
                            'user_profile_id' => $userProfileId ?? $billingAddress->user_profile_id,
                        ]);
                        Log::info("✅ Updated existing billing address ID: {$billingAddress->id}");
                    }
                } else {
                    // Create a new address
                    $billingAddress = Address::create([
                        'address' => $request->input('billing_address'),
                        'city' => $request->input('billing_city'),
                        'state' => $request->input('billing_state'),
                        'country' => $request->input('billing_country'),
                        'zipcode' => $request->input('billing_zipcode'),
                        'type' => 'billing',
                        'user_profile_id' => $userProfileId,
                    ]);
                    Log::info("✅ Created new billing address ID: {$billingAddress->id}");
                }

                $billingAddressId = $billingAddress->id;
            } else {
                $billingAddressId = $order->billing_address_id;
            }

            // Process shipping addresses if they exist
            $shippingAddressId = null;
            if ($request->has('shipping_addresses') && is_array($request->input('shipping_addresses')) && !empty($request->input('shipping_addresses'))) {
                Log::info("🔄 Processing shipping address");
                $shippingAddressData = $request->input('shipping_addresses')[0]; // Get first shipping address

                // Fetch the current shipping address or create a new one
                if ($order->shipping_address_id) {
                    $shippingAddress = Address::find($order->shipping_address_id);
                    if ($shippingAddress) {
                        $shippingAddress->update([
                            'address' => $shippingAddressData['address'] ?? null,
                            'city' => $shippingAddressData['city'] ?? null,
                            'state' => $shippingAddressData['state'] ?? null,
                            'country' => $shippingAddressData['country'] ?? null,
                            'zipcode' => $shippingAddressData['zipcode'] ?? null,
                            // Only update user_profile_id if we have it and it's not already set
                            'user_profile_id' => $userProfileId ?? $shippingAddress->user_profile_id,
                        ]);
                        Log::info("✅ Updated existing shipping address ID: {$shippingAddress->id}");
                    }
                } else {
                    // Create a new address
                    $shippingAddress = Address::create([
                        'address' => $shippingAddressData['address'] ?? null,
                        'city' => $shippingAddressData['city'] ?? null,
                        'state' => $shippingAddressData['state'] ?? null,
                        'country' => $shippingAddressData['country'] ?? null,
                        'zipcode' => $shippingAddressData['zipcode'] ?? null,
                        'type' => 'shipping',
                        'user_profile_id' => $userProfileId,
                    ]);
                    Log::info("✅ Created new shipping address ID: {$shippingAddress->id}");
                }

                $shippingAddressId = $shippingAddress->id;
            } else {
                $shippingAddressId = $order->shipping_address_id;
            }

            // Update the order with simple fields first
            Log::info("🔄 Updating order basic information");
            $updateData = [
                'user_id' => $request->input('user_id'),
                'billing_address_id' => $billingAddressId,
                'shipping_address_id' => $shippingAddressId,
                'shipping_condition_id' => $request->input('shipping_condition_id'),
                'status' => $request->input('status'),
            ];

            // Log update data
            Log::info("📊 Order update data: " . json_encode($updateData));

            // Perform update
            $order->fill($updateData);
            $updateResult = $order->save();

            if ($updateResult) {
                Log::info("✅ Order basic info updated successfully");
            } else {
                Log::warning("⚠️ Order basic info update failed");
            }

            // Process order items if they exist
            if ($request->has('artworks') && $request->has('quantities')) {
                Log::info("🔄 Processing order items");

                // Track for deletion
                $keepArtworkIds = [];
                $totalAmount = 0;

                // Process each artwork
                $artworkIds = $request->input('artworks');
                $quantities = $request->input('quantities');

                foreach ($artworkIds as $artworkId) {
                    // Skip if no quantity or zero quantity
                    if (!isset($quantities[$artworkId]) || intval($quantities[$artworkId]) <= 0) {
                        Log::info("⏭️ Skipping Artwork ID: {$artworkId} due to missing or zero quantity");
                        continue;
                    }

                    $quantity = intval($quantities[$artworkId]);
                    $keepArtworkIds[] = $artworkId;

                    try {
                        $artwork = Artwork::findOrFail($artworkId);
                        $price = $artwork->initial_price;
                        $itemTotal = $price * $quantity;
                        $totalAmount += $itemTotal;

                        // Find existing item or create new
                        $item = OrderItem::updateOrCreate(
                            [
                                'order_id' => $order->id,
                                'artwork_id' => $artworkId
                            ],
                            [
                                'quantity' => $quantity,
                                'price' => $price,
                                'total' => $itemTotal
                            ]
                        );

                        Log::info("✅ Updated/created order item for Artwork ID: {$artworkId}, Quantity: {$quantity}, Price: {$price}");
                    } catch (\Exception $e) {
                        Log::error("❌ Failed to process item for Artwork ID: {$artworkId} - " . $e->getMessage());
                    }
                }

                // Remove items not in the keep list
                if (!empty($keepArtworkIds)) {
                    $deletedCount = $order->items()->whereNotIn('artwork_id', $keepArtworkIds)->delete();
                    Log::info("🗑️ Deleted {$deletedCount} items not present in updated list");
                }

                // Update order total
                $order->total = $totalAmount;
                $order->save();
                Log::info("💰 Updated order total: {$totalAmount}");
            }

            Log::info("✅ Order update process completed successfully for Order ID: {$order->id}");
            return redirect()->route('admin.orders.show', $order)->with('success', __('Order updated successfully.'));
        } catch (Throwable $e) {
            Log::error("❌ Error updating order (Order ID: {$order->id}): " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            return back()->with('error', __('Failed to update order.'));
        }
    }

    public function edit(Request $request, Order $order)
    {
        try {
            isAllowed($request->user());

            $order->load([
                'items.artwork',
                'user.profile.billingAddress',
                'user.profile.shippingAddresses',
                'billingAddress',
                'shippingAddress',
                'shippingCondition',
            ]);

            return view('admin.orders.edit', [
                'order' => $order,
                'users' => User::with('profile')->get(),
                'billingAddress' => $order->billingAddress,
                'shippingAddresses' => $order->user->profile->shippingAddresses ?? collect(),
                'shippingConditions' => ShippingCondition::all(),
            ]);
        } catch (Throwable $e) {
            Log::error("❌ Error loading order for editing (Order ID: {$order->id}): " . $e->getMessage());
            return redirect()->route('admin.orders.index')->with('error', __('Error loading order for editing.'));
        }
    }

    public function destroy(Request $request, Order $order)
    {
        try {
            isAllowed($request->user());

            $order->delete();

            return back()->with('success', __('Order deleted successfully.'));
        } catch (Throwable $e) {
            Log::error("❌ Error deleting order (Order ID: {$order->id}): " . $e->getMessage());
            return back()->with('error', __('Failed to delete order.'));
        }
    }

    public function trashed(Request $request)
    {
        try {
            isAllowed($request->user());

            $orders = Order::onlyTrashed()->paginate(10);

            return view('admin.orders.trashed', compact('orders'));
        } catch (Throwable $e) {
            Log::error("❌ Error loading trashed orders: " . $e->getMessage());
            return redirect()->route('admin.orders.index')->with('error', __('Error loading trashed orders.'));
        }
    }

    public function restore(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $order = Order::onlyTrashed()->findOrFail($id);
            $order->restore();

            return redirect()->route('admin.orders.trashed')->with('success', __('Order restored successfully.'));
        } catch (Throwable $e) {
            Log::error("❌ Error restoring order (Order ID: {$id}): " . $e->getMessage());
            return back()->with('error', __('Error restoring order.'));
        }
    }

    public function forceDelete(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $order = Order::onlyTrashed()->findOrFail($id);
            $order->forceDelete();

            return redirect()->route('admin.orders.trashed')->with('success', __('Order permanently deleted.'));
        } catch (Throwable $e) {
            Log::error("❌ Error permanently deleting order (Order ID: {$id}): " . $e->getMessage());
            return back()->with('error', __('Error permanently deleting order.'));
        }
    }
}
