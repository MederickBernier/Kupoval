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
    /**
     * Display a listing of the orders.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
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

    /**
     * Display the specified order details.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
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

    /**
     * Display the form for creating a new order.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
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

    /**
     * Store a newly created order in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
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

    /**
     * Update the specified order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function update(Request $request, Order $order)
    {
        try {
            Log::info("🔄 Starting order update process for Order ID: {$order->id}");
            Log::info("📝 Request data: " . json_encode($request->all()));

            isAllowed($request->user());
            Log::info("✅ Authorization check passed for user: {$request->user()->id}");

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

            if ($request->has('billing_address')) {
                Log::info("🔄 Processing billing address");

                if ($order->billing_address_id) {
                    $billingAddress = Address::find($order->billing_address_id);
                    if ($billingAddress) {
                        $billingAddress->update([
                            'address' => $request->input('billing_address'),
                            'city' => $request->input('billing_city'),
                            'state' => $request->input('billing_state'),
                            'country' => $request->input('billing_country'),
                            'zipcode' => $request->input('billing_zipcode'),
                            'user_profile_id' => $userProfileId ?? $billingAddress->user_profile_id,
                        ]);
                        Log::info("✅ Updated existing billing address ID: {$billingAddress->id}");
                    }
                } else {
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

            $shippingAddressId = null;
            if ($request->has('shipping_addresses') && is_array($request->input('shipping_addresses')) && !empty($request->input('shipping_addresses'))) {
                Log::info("🔄 Processing shipping address");
                $shippingAddressData = $request->input('shipping_addresses')[0];

                if ($order->shipping_address_id) {
                    $shippingAddress = Address::find($order->shipping_address_id);
                    if ($shippingAddress) {
                        $shippingAddress->update([
                            'address' => $shippingAddressData['address'] ?? null,
                            'city' => $shippingAddressData['city'] ?? null,
                            'state' => $shippingAddressData['state'] ?? null,
                            'country' => $shippingAddressData['country'] ?? null,
                            'zipcode' => $shippingAddressData['zipcode'] ?? null,
                            'user_profile_id' => $userProfileId ?? $shippingAddress->user_profile_id,
                        ]);
                        Log::info("✅ Updated existing shipping address ID: {$shippingAddress->id}");
                    }
                } else {
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

            Log::info("🔄 Updating order basic information");
            $updateData = [
                'user_id' => $request->input('user_id'),
                'billing_address_id' => $billingAddressId,
                'shipping_address_id' => $shippingAddressId,
                'shipping_condition_id' => $request->input('shipping_condition_id'),
                'status' => $request->input('status'),
            ];

            Log::info("📊 Order update data: " . json_encode($updateData));

            $order->fill($updateData);
            $updateResult = $order->save();

            if ($updateResult) {
                Log::info("✅ Order basic info updated successfully");
            } else {
                Log::warning("⚠️ Order basic info update failed");
            }

            if ($request->has('artworks') && $request->has('quantities')) {
                Log::info("🔄 Processing order items");

                $keepArtworkIds = [];
                $totalAmount = 0;

                $artworkIds = $request->input('artworks');
                $quantities = $request->input('quantities');

                foreach ($artworkIds as $artworkId) {
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

                if (!empty($keepArtworkIds)) {
                    $deletedCount = $order->items()->whereNotIn('artwork_id', $keepArtworkIds)->delete();
                    Log::info("🗑️ Deleted {$deletedCount} items not present in updated list");
                }

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

    /**
     * Edit the specified order.
     *
     * @param \Illuminate\Http\Request $request The current request instance.
     * @param \App\Models\Order $order The order instance to be edited.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse The view for editing the order or a redirect response on error.
     */
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

    /**
     * Remove the specified order from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Display a listing of the trashed orders.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Restore a soft-deleted order.
     *
     * @param \Illuminate\Http\Request $request The current request instance.
     * @param int $id The ID of the order to restore.
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the order is not found.
     * @throws \Throwable If any other error occurs during the restore process.
     */
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

    /**
     * Permanently delete a trashed order.
     *
     * @param \Illuminate\Http\Request $request The current request instance.
     * @param int $id The ID of the order to be permanently deleted.
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the order is not found.
     * @throws \Throwable If any error occurs during the deletion process.
     */
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
