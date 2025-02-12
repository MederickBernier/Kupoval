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
            $orders = Order::latest()->paginate(10);

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

            return view('admin.orders.show', [
                'order' => $order,
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

                'use_different_shipping' => 'required|boolean',

                // Shipping fields required only if 'use_different_shipping' is true
                'shipping_address' => 'nullable|string|required_if:use_different_shipping,true',
                'shipping_city' => 'nullable|string|required_if:use_different_shipping,true',
                'shipping_state' => 'nullable|string|required_if:use_different_shipping,true',
                'shipping_country' => 'nullable|string|required_if:use_different_shipping,true',
                'shipping_zipcode' => 'nullable|string|required_if:use_different_shipping,true',

                'shipping_condition_id' => 'required|exists:shipping_conditions,id',

                'recipient_name' => 'nullable|string',
                'recipient_email' => 'nullable|email',
                'recipient_phone' => 'nullable|string',
            ]);

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
            if ($validatedData['use_different_shipping']) {
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
                'recipient_phone' => $validatedData['recipient_phone'],
            ]);

            // Attach Artworks with Quantities
            foreach ($validatedData['artworks'] as $index => $artworkId) {
                $artwork = Artwork::find($artworkId);

                if ($artwork) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'artwork_id' => $artworkId,
                        'quantity' => $validatedData['quantities'][$index],
                        'unit_price' => $artwork->initial_price, // Ensure correct pricing
                    ]);
                }
            }

            return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating order', 'error' => $e->getMessage()], 500);
        }
    }
}
