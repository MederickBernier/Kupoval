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

            $order->load([
                'user.profile',
                'billingAddress',
                'shippingAddress',
                'shippingCondition',
                'items.artwork'
            ]);

            $shippingConditions = ShippingCondition::all();

            return view('admin.orders.show', [
                'order' => $order,
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

            $order->load([
                'items.artwork',
                'user',
                'billingAddress',
                'shippingAddress',
                'shippingCondition',
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

                'shipping_address' => 'nullable|string',
                'shipping_city' => 'nullable|string',
                'shipping_state' => 'nullable|string',
                'shipping_country' => 'nullable|string',
                'shipping_zipcode' => 'nullable|string',

                'shipping_condition_id' => 'required|exists:shipping_conditions,id',
                'recipient_name' => 'nullable|string',
                'recipient_email' => 'nullable|email',
                'recipient_phone' => 'nullable|string',
            ]);

            $billingAddress = Address::create([
                'user_id' => $validatedData['user_id'],
                'address' => $validatedData['billing_address'],
                'city' => $validatedData['billing_city'],
                'state' => $validatedData['billing_state'],
                'country' => $validatedData['billing_country'],
                'zipcode' => $validatedData['billing_zipcode'],
            ]);

            $shippingAddress = null;
            if (!empty($validatedData['shipping_address'])) {
                $shippingAddress = Address::create([
                    'user_id' => $validatedData['user_id'],
                    'address' => $validatedData['shipping_address'],
                    'city' => $validatedData['shipping_city'],
                    'state' => $validatedData['shipping_state'],
                    'country' => $validatedData['shipping_country'],
                    'zipcode' => $validatedData['shipping_zipcode'],
                ]);
            }

            $order = Order::create([
                'user_id' => $validatedData['user_id'],
                'billing_address_id' => $billingAddress->id,
                'shipping_address_id' => $shippingAddress ? $shippingAddress->id : null,
                'total' => $validatedData['total_price'],
                'shipping_condition_id' => $validatedData['shipping_condition_id'],
                'recipient_name' => $validatedData['recipient_name'] ?? 'Unknown Recipient',
                'recipient_email' => $validatedData['recipient_email'] ?? User::find($validatedData['user_id'])->email,
                'recipient_phone' => $validatedData['recipient_phone'] ?? null,
            ]);

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

            $order->update([
                'user_id' => $validatedData['user_id'],
                'status' => $validatedData['status'],
                'shipping_condition_id' => $validatedData['shipping_condition_id'],
            ]);

            $existingItems = $order->items->keyBy('artwork_id');

            foreach ($validatedData['artworks'] as $artworkId) {
                if (isset($existingItems[$artworkId])) {
                    $existingItems[$artworkId]->update([
                        'quantity' => $validatedData['quantities'][$artworkId],
                    ]);
                } else {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'artwork_id' => $artworkId,
                        'quantity' => $validatedData['quantities'][$artworkId],
                        'unit_price' => Artwork::find($artworkId)->initial_price,
                    ]);
                }
            }

            $existingItems->except(array_keys($validatedData['artworks']))->each->delete();

            return redirect()->route('admin.orders.index')->with('success', __('admin/orders.updated_successfully'));
        } catch (\Exception $e) {
            return back()->withErrors(__('Error updating order') . ': ' . $e->getMessage());
        }
    }
}
