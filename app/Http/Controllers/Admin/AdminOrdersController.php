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

class AdminOrdersController extends Controller
{
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());

            $orders = Order::with([
                'user.profile',
                'payment' // Fetch the single payment for each order
            ])->latest()->paginate(10);

            return view('admin.orders.index', [
                'orders' => $orders,
            ]);
        } catch (\Exception $e) {
            throwError(__('Error loading orders list'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function show(Request $request, Order $order)
    {
        try {
            isAllowed($request->user());

            // Load all necessary relationships, ensuring addresses exist
            $order->load([
                'user.profile',
                'billingAddress',
                'shippingAddress',
                'shippingCondition',
                'items.artwork'
            ]);

            // Check if shipping address exists or falls back to billing
            $shippingAddress = $order->shippingAddress ?? $order->billingAddress;

            // Ensure shipping conditions are available
            $shippingConditions = ShippingCondition::all();

            return view('admin.orders.show', [
                'order' => $order,
                'billingAddress' => $order->billingAddress,
                'shippingAddress' => $shippingAddress,
                'shippingConditions' => $shippingConditions,
            ]);
        } catch (\Exception $e) {
            throwError(__('Error loading order details'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function create(Request $request)
    {
        try {
            isAllowed($request->user());

            $users = User::with('profile.addresses')->get();
            $artworks = Artwork::select('id', 'name', 'initial_price as price', 'image')->get();
            $shippingConditions = ShippingCondition::all();

            return view('admin.orders.create', [
                'users' => $users,
                'artworks' => $artworks,
                'shippingConditions' => $shippingConditions,
            ]);
        } catch (\Exception $e) {
            throwError(__('Error loading order creation form'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function edit(Request $request, Order $order)
    {
        try {
            isAllowed($request->user());

            // Eager load relationships to prevent N+1 query issues
            $order->load([
                'items.artwork',
                'user.profile.billingAddress',
                'user.profile.shippingAddresses',
                'billingAddress',
                'shippingAddress',
                'shippingCondition',
            ]);

            // Ensure profile exists before accessing properties
            $profile = $order->user->profile ?? null;

            return view('admin.orders.edit', [
                'order' => $order,
                'users' => User::with('profile')->get(),
                'billingAddress' => $profile ? $profile->billingAddress : null,
                'shippingAddresses' => $profile ? $profile->shippingAddresses : collect(),
                'shippingConditions' => ShippingCondition::all(),
            ]);
        } catch (\Exception $e) {
            throwError(__('Error loading order for editing'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
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
                'user_id' => $validatedData['user_id'],
                'billing_address_id' => $validatedData['billing_address_id'],
                'shipping_address_id' => $validatedData['shipping_address_id'] ?? null,
                'total' => $validatedData['total_price'],
                'stripe_session_id' => 'manual_test_' . uniqid(),
                'shipping_condition_id' => $validatedData['shipping_condition_id'],
                'recipient_name' => $validatedData['recipient_name'] ?? 'Unknown Recipient',
                'recipient_email' => $validatedData['recipient_email'] ?? User::find($validatedData['user_id'])->email,
                'recipient_phone' => $validatedData['recipient_phone'] ?? null,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.index')->with('error', 'Error creating order: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Order $order)
    {
        try {
            isAllowed($request->user());
            Log::info("Updating order: {$order->id}");

            Log::info("Incoming Request Data: ", $request->all());

            // Validation
            try {
                $validatedData = $request->validate([
                    'user_id' => 'required|exists:users,id',
                    'status' => 'required|in:pending,completed,canceled,refunded',
                    'billing_address' => 'required|string',
                    'billing_city' => 'required|string',
                    'billing_state' => 'required|string',
                    'billing_country' => 'required|string',
                    'billing_zipcode' => 'required|string',
                    'shipping_condition_id' => 'required|exists:shipping_conditions,id',
                    'artworks' => 'required|array|min:1',
                    'artworks.*' => 'exists:artworks,id',
                    'quantities' => 'required|array',
                ]);
            } catch (ValidationException $e) {
                Log::error("Validation failed: ", $e->errors());
                return back()->withErrors($e->errors())->withInput();
            }

            Log::info("Validation passed for order: {$order->id}");

            // Update Order details
            $order->update([
                'user_id' => $validatedData['user_id'],
                'status' => $validatedData['status'],
                'shipping_condition_id' => $validatedData['shipping_condition_id'],
            ]);

            Log::info("Order details updated for order: {$order->id}");

            // Update Billing Address
            $billingAddress = $order->billingAddress ?? new Address();
            $billingAddress->fill([
                'user_id' => $validatedData['user_id'],
                'address' => $validatedData['billing_address'],
                'city' => $validatedData['billing_city'],
                'state' => $validatedData['billing_state'],
                'country' => $validatedData['billing_country'],
                'zipcode' => $validatedData['billing_zipcode'],
                'type' => 'billing',
            ])->save();

            $order->billing_address_id = $billingAddress->id;
            $order->save();

            Log::info("Billing address updated for order: {$order->id}");

            // Handle Shipping Address
            if ($request->has('shipping_address') && $request->input('shipping_address') !== $validatedData['billing_address']) {
                $shippingAddress = $order->shippingAddress ?? new Address();
                $shippingAddress->fill([
                    'user_id' => $validatedData['user_id'],
                    'address' => $request->input('shipping_address'),
                    'city' => $request->input('shipping_city'),
                    'state' => $request->input('shipping_state'),
                    'country' => $request->input('shipping_country'),
                    'zipcode' => $request->input('shipping_zipcode'),
                    'type' => 'shipping',
                ])->save();

                $order->shipping_address_id = $shippingAddress->id;
                $order->save();
                Log::info("Shipping address updated for order: {$order->id}");
            } elseif (!$request->has('shipping_address')) {
                $order->shipping_address_id = null;
                $order->save();
                Log::info("No shipping address provided, set to null for order: {$order->id}");
            }

            // Handle Order Items (Update, Add, Remove)
            $existingItems = $order->items->keyBy('artwork_id');
            Log::info("Processing order items for order: {$order->id}");

            foreach ($validatedData['artworks'] as $artworkId) {
                if (isset($existingItems[$artworkId])) {
                    // Update existing order item
                    $existingItems[$artworkId]->update([
                        'quantity' => $validatedData['quantities'][$artworkId],
                    ]);
                    Log::info("Updated item: Artwork ID {$artworkId} in order: {$order->id}");
                } else {
                    // Add new order item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'artwork_id' => $artworkId,
                        'quantity' => $validatedData['quantities'][$artworkId],
                        'unit_price' => Artwork::find($artworkId)->initial_price,
                    ]);
                    Log::info("Added new item: Artwork ID {$artworkId} to order: {$order->id}");
                }
            }

            // Remove items that were deleted
            $removedItems = $existingItems->except(array_keys($validatedData['artworks']));
            $removedItems->each(function ($item) {
                Log::info("Removing item: Artwork ID {$item->artwork_id} from order: {$item->order_id}");
                $item->delete();
            });

            Log::info("Order update completed successfully for order: {$order->id}");

            return redirect()->route('admin.orders.index')->with('success', __('admin/orders.updated_successfully'));
        } catch (\Exception $e) {
            Log::error("Error updating order: {$order->id} - " . $e->getMessage());
            return back()->withErrors(__('Error updating order') . ': ' . $e->getMessage());
        }
    }
}
