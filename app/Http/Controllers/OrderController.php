<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display the details of an order.
     */
    public function show(Order $order)
    {
        try {
            // 🔹 Ensure the order belongs to the authenticated user
            if ($order->user_id !== Auth::id()) {
                Log::warning("⚠️ Unauthorized access attempt to Order #{$order->id}", [
                    'user_id' => Auth::id(),
                    'order_id' => $order->id
                ]);
                return redirect()->route('shop.index')->with('error', __('Unauthorized access to this order.'));
            }

            Log::info("✅ Order #{$order->id} accessed successfully by user.", ['user_id' => Auth::id()]);

            return view('public.orders.show', compact('order'));
        } catch (\Exception $e) {
            Log::error("❌ Failed to load Order #{$order->id}", ['error' => $e->getMessage()]);
            return redirect()->route('shop.index')->with('error', __('An error occurred while loading the order.'));
        }
    }
}
