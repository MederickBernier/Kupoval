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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminOrdersController extends Controller
{
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());
            $orders = Order::with(['user.profile'])->latest()->paginate(10);

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

            // Eager load necessary relationships while avoiding soft delete issues
            $order->load([
                'user.profile',                 // Customer details
                'billingAddress',               // Billing address
                'shippingAddress',              // Shipping address
                'shippingCondition',            // Shipping condition details
                'items.artwork'                 // Order items with artwork details
            ]);

            // Fetch all available shipping conditions to compare
            $shippingConditions = ShippingCondition::all();

            return view('admin.orders.show', [
                'order' => $order,
                'shippingConditions' => $shippingConditions, // Pass shipping conditions to view
            ]);
        } catch (\Exception $e) {
            throwError(__('Error loading order details'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function create(Request $request)
    {
        try {
            isAllowed($request->user());

            $users = User::with('profile')->get();
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

            // Load related data
            $order->load([
                'items.artwork', // Ensure order items with artworks are loaded
                'user', // Load user data
                'billingAddress', // Load billing address
                'shippingAddress', // Load shipping address
                'shippingCondition', // Load shipping condition
            ]);

            return view('admin.orders.edit', [
                'order' => $order,
                'users' => User::all(),
                'shippingConditions' => ShippingCondition::all(),
            ]);
        } catch (\Exception $e) {
            throwError(__('Error loading order for editing'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validate request
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'artworks' => 'required|array|min:1',
                'artworks.*' => 'exists:artworks,id',
                'quantities' => 'required|array',
                'quantities.*' => 'integer|min:1',
                'total_price' => 'required|numeric|min:0',

                'billing_address' => 'required|string',
                'billing_city' => 'required|string',
                'billing_state' => 'required|string',
                'billing_country' => 'required|string',
                'billing_zipcode' => 'required|string',

                'use_different_shipping' => 'sometimes|boolean',

                'shipping_address' => 'nullable|string|required_if:use_different_shipping,1',
                'shipping_city' => 'nullable|string|required_if:use_different_shipping,1',
                'shipping_state' => 'nullable|string|required_if:use_different_shipping,1',
                'shipping_country' => 'nullable|string|required_if:use_different_shipping,1',
                'shipping_zipcode' => 'nullable|string|required_if:use_different_shipping,1',

                'shipping_condition_id' => 'required|exists:shipping_conditions,id',

                'recipient_name' => 'nullable|string',
                'recipient_email' => 'nullable|email',
                'recipient_phone' => 'nullable|string',
            ]);

            // Set default values
            $validatedData['recipient_name'] = $validatedData['recipient_name'] ?? 'Unknown Recipient';
            $validatedData['recipient_email'] = $validatedData['recipient_email'] ?? User::find($validatedData['user_id'])->email;

            // Store billing address
            $billingAddress = Address::create([
                'user_id' => $validatedData['user_id'],
                'address' => $validatedData['billing_address'],
                'city' => $validatedData['billing_city'],
                'state' => $validatedData['billing_state'],
                'country' => $validatedData['billing_country'],
                'zipcode' => $validatedData['billing_zipcode'],
            ]);

            // Store shipping address if different
            $shippingAddress = null;
            if (!empty($validatedData['use_different_shipping']) && $validatedData['use_different_shipping']) {
                $shippingAddress = Address::create([
                    'user_id' => $validatedData['user_id'],
                    'address' => $validatedData['shipping_address'],
                    'city' => $validatedData['shipping_city'],
                    'state' => $validatedData['shipping_state'],
                    'country' => $validatedData['shipping_country'],
                    'zipcode' => $validatedData['shipping_zipcode'],
                ]);
            }

            // Create Order
            $order = Order::create([
                'user_id' => $validatedData['user_id'],
                'billing_address_id' => $billingAddress->id,
                'shipping_address_id' => $shippingAddress ? $shippingAddress->id : null,
                'total' => $validatedData['total_price'],
                'shipping_condition_id' => $validatedData['shipping_condition_id'],
                'recipient_name' => $validatedData['recipient_name'],
                'recipient_email' => $validatedData['recipient_email'],
                'recipient_phone' => $validatedData['recipient_phone'] ?? null,
            ]);

            // Attach Artworks with Quantities
            foreach ($validatedData['artworks'] as $index => $artworkId) {
                $artwork = Artwork::find($artworkId);

                if ($artwork) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'artwork_id' => $artworkId,
                        'quantity' => $validatedData['quantities'][$index] ?? 1,
                        'unit_price' => $artwork->initial_price,
                    ]);
                }
            }

            return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.index')->with('error', 'Error creating order: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Order $order)
    {
        try {
            isAllowed($request->user());
            Log::info('Updating order process started', ['order_id' => $order->id]);

            // Validate request
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'status' => 'required|in:pending,completed,canceled,refunded',
                'billing_address' => 'required|string',
                'billing_city' => 'required|string',
                'billing_state' => 'required|string',
                'billing_country' => 'required|string',
                'billing_zipcode' => 'required|string',
                'shipping_address' => 'nullable|string',
                'shipping_city' => 'nullable|string',
                'shipping_state' => 'nullable|string',
                'shipping_country' => 'nullable|string',
                'shipping_zipcode' => 'nullable|string',
                'shipping_condition_id' => 'required|exists:shipping_conditions,id',
                'artworks' => 'required|array|min:1',
                'artworks.*' => 'exists:artworks,id',
                'quantities' => 'required|array',
            ]);

            Log::info('Validation successful', ['validated_data' => $validatedData]);

            // Update billing address
            $billingAddress = $order->billingAddress;
            if ($billingAddress) {
                $billingAddress->update([
                    'address' => $validatedData['billing_address'],
                    'city' => $validatedData['billing_city'],
                    'state' => $validatedData['billing_state'],
                    'country' => $validatedData['billing_country'],
                    'zipcode' => $validatedData['billing_zipcode'],
                ]);
                Log::info('Billing address updated', ['billing_address_id' => $billingAddress->id]);
            }

            // Update shipping address
            if (!empty($validatedData['shipping_address'])) {
                if ($order->shippingAddress) {
                    $order->shippingAddress->update([
                        'address' => $validatedData['shipping_address'],
                        'city' => $validatedData['shipping_city'],
                        'state' => $validatedData['shipping_state'],
                        'country' => $validatedData['shipping_country'],
                        'zipcode' => $validatedData['shipping_zipcode'],
                    ]);
                    Log::info('Shipping address updated', ['shipping_address_id' => $order->shippingAddress->id]);
                } else {
                    $shippingAddress = Address::create([
                        'user_id' => $validatedData['user_id'],
                        'address' => $validatedData['shipping_address'],
                        'city' => $validatedData['shipping_city'],
                        'state' => $validatedData['shipping_state'],
                        'country' => $validatedData['shipping_country'],
                        'zipcode' => $validatedData['shipping_zipcode'],
                    ]);
                    $order->shipping_address_id = $shippingAddress->id;
                    Log::info('New shipping address created', ['shipping_address_id' => $shippingAddress->id]);
                }
            } else {
                $order->shipping_address_id = null;
                Log::info('Shipping address removed');
            }

            // Update order details
            $order->update([
                'user_id' => $validatedData['user_id'],
                'status' => $validatedData['status'],
                'shipping_condition_id' => $validatedData['shipping_condition_id'],
            ]);
            Log::info('Order details updated', ['order_id' => $order->id, 'status' => $order->status]);

            // Prevent deletion if no artworks exist
            if (!isset($validatedData['artworks']) || empty($validatedData['artworks'])) {
                Log::warning('No artworks provided, skipping order item deletion', ['order_id' => $order->id]);
            } else {
                Log::info('Removing old order items', ['order_id' => $order->id]);
                $order->items()->delete();

                // Re-insert order items
                foreach ($validatedData['artworks'] as $artworkId) {
                    if (!isset($validatedData['quantities'][$artworkId])) {
                        Log::error('Quantity missing for artwork', ['artwork_id' => $artworkId]);
                        continue;
                    }

                    $artwork = Artwork::find($artworkId);
                    if ($artwork) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'artwork_id' => $artworkId,
                            'quantity' => $validatedData['quantities'][$artworkId],
                            'unit_price' => $artwork->initial_price,
                        ]);
                        Log::info('Order item added', ['artwork_id' => $artworkId, 'quantity' => $validatedData['quantities'][$artworkId]]);
                    } else {
                        Log::warning('Artwork not found', ['artwork_id' => $artworkId]);
                    }
                }
            }

            Log::info('Order update process completed successfully', ['order_id' => $order->id]);

            return redirect()->route('admin.orders.index')->with('success', __('admin/orders.updated_successfully'));
        } catch (\Exception $e) {
            Log::error('Error updating order', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return back()->withErrors(__('Error updating order') . ': ' . $e->getMessage());
        }
    }
}
