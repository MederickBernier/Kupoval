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
            isAllowed($request->user());

            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'billing_address_id' => 'required|exists:addresses,id',
                'shipping_address_id' => 'nullable|exists:addresses,id',
                'shipping_condition_id' => 'required|exists:shipping_conditions,id',
                'total_price' => 'required|numeric|min:0',
                'status' => 'nullable|string',
                'recipient_name' => 'nullable|string',
                'recipient_email' => 'nullable|email',
                'recipient_phone' => 'nullable|string',
            ]);

            $order->update([
                'user_id' => $validated['user_id'],
                'billing_address_id' => $validated['billing_address_id'],
                'shipping_address_id' => $validated['shipping_address_id'] ?? null,
                'shipping_condition_id' => $validated['shipping_condition_id'],
                'total' => $validated['total_price'],
                'status' => $validated['status'] ?? $order->status,
                'recipient_name' => $validated['recipient_name'] ?? $order->recipient_name,
                'recipient_email' => $validated['recipient_email'] ?? $order->recipient_email,
                'recipient_phone' => $validated['recipient_phone'] ?? $order->recipient_phone,
            ]);

            // Handle order items if requested
            if ($request->has('artworks') && $request->has('quantities')) {
                // Remove existing items
                $order->items()->delete();

                // Add new items
                $artworks = $request->input('artworks');
                $quantities = $request->input('quantities');

                foreach ($artworks as $key => $artworkId) {
                    if (isset($quantities[$key]) && $quantities[$key] > 0) {
                        $artwork = Artwork::findOrFail($artworkId);
                        OrderItem::create([
                            'order_id' => $order->id,
                            'artwork_id' => $artworkId,
                            'quantity' => $quantities[$key],
                            'price' => $artwork->initial_price,
                            'total' => $artwork->initial_price * $quantities[$key],
                        ]);
                    }
                }

                // Recalculate order total if needed
                if ($request->has('recalculate_total') && $request->input('recalculate_total')) {
                    $newTotal = $order->items->sum('total');
                    $order->update(['total' => $newTotal]);
                }
            }

            return redirect()->route('admin.orders.show', $order)->with('success', __('Order updated successfully.'));
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            Log::error("❌ Error updating order (Order ID: {$order->id}): " . $e->getMessage());
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
