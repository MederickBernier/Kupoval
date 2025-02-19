<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ShippingCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function createCheckoutSession()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You need to login to proceed to checkout');
        }

        $cart = Cart::where('user_id', Auth::id())->with('items.artwork')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('shop')->with('error', 'Your cart is empty');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        // Retrieve session values
        $cartTotal = session('cart_total', $cart->items->sum(fn($item) => $item->quantity * $item->price));
        $discountAmount = session('discount_amount', 0);
        $shippingFee = session('shipping_fee', 0);

        // Ensure a valid shipping condition is set
        $shippingConditionId = session('shipping_condition_id', null);
        if (!$shippingConditionId) {
            $defaultShipping = \App\Models\ShippingCondition::orderBy('fee', 'asc')->first();
            if ($defaultShipping) {
                $shippingConditionId = $defaultShipping->id;
                session(['shipping_condition_id' => $defaultShipping->id]);
            } else {
                Log::error("No shipping conditions available, unable to proceed.");
                return redirect()->route('checkout.confirmation')->with('error', 'Shipping option is required.');
            }
        }

        // Convert amounts to cents (Stripe format)
        $cartTotalCents = max((int) round($cartTotal * 100), 50);
        $discountAmountCents = min((int) round($discountAmount * 100), $cartTotalCents);
        $shippingFeeCents = max((int) round($shippingFee * 100), 0);
        $finalTotalCents = max($cartTotalCents - $discountAmountCents + $shippingFeeCents, 50);

        Log::info('Stripe Checkout Debug:', [
            'cartTotalCents' => $cartTotalCents,
            'discountAmountCents' => $discountAmountCents,
            'shippingFeeCents' => $shippingFeeCents,
            'finalTotalCents' => $finalTotalCents,
            'shippingConditionId' => $shippingConditionId
        ]);

        // Add each cart item
        $lineItems = [];
        foreach ($cart->items as $item) {
            $unitAmountCents = max((int) round($item->price * 100), 50);

            Log::info('Item Price Debug:', [
                'artwork' => $item->artwork->name,
                'unit_amount' => $unitAmountCents,
                'quantity' => $item->quantity
            ]);

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'cad',
                    'product_data' => ['name' => $item->artwork->name],
                    'unit_amount' => $unitAmountCents,
                ],
                'quantity' => $item->quantity,
            ];
        }

        // Add shipping fee if applicable
        if ($shippingFeeCents > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'cad',
                    'product_data' => ['name' => 'Shipping Fee'],
                    'unit_amount' => $shippingFeeCents,
                ],
                'quantity' => 1,
            ];
        }

        try {
            // Create Stripe checkout session
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),
                'customer_email' => Auth::user()->email,
            ]);

            Log::info("Stripe Checkout Session Created: {$checkoutSession->id}");
            return redirect($checkoutSession->url);
        } catch (\Exception $e) {
            Log::error('Stripe Error:', ['message' => $e->getMessage()]);
            return redirect()->route('checkout.confirmation')->with('error', 'Payment could not be processed.');
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('shop.index')->with('error', 'Payment was not successful');
        }

        $cart = Cart::where('user_id', Auth::id())->with('items.artwork')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty');
        }

        // Retrieve necessary session data
        $totalPrice = session('cart_total', $cart->items->sum(fn($item) => $item->quantity * $item->price));
        $shippingConditionId = session('shipping_condition_id', null);

        // Ensure `shipping_condition_id` is NOT null
        if (!$shippingConditionId) {
            $defaultShipping = ShippingCondition::orderBy('fee', 'asc')->first(); // Select the cheapest shipping method
            $shippingConditionId = $defaultShipping ? $defaultShipping->id : null;
        }

        // Ensure valid `shipping_condition_id` before inserting
        if (!$shippingConditionId) {
            return redirect()->route('checkout.confirmation')->with('error', 'Shipping selection is required.');
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'shipping_condition_id' => $shippingConditionId,
            'status' => 'pending',
            'total' => $totalPrice,
            'billing_address_id' => Auth::user()->billing_address_id ?? null,
            'shipping_address_id' => Auth::user()->shipping_address_id ?? null,
            'recipient_name' => Auth::user()->name,
            'recipient_email' => Auth::user()->email,
            'recipient_phone' => Auth::user()->phone ?? null,
            'stripe_session_id' => $sessionId,
        ]);

        foreach ($cart->items as $item) {
            // Ensure price is always a valid number
            $unitPrice = max((float) $item->price, 0.01);

            OrderItem::create([
                'order_id' => $order->id,
                'artwork_id' => $item->artwork_id,
                'quantity' => $item->quantity,
                'unit_price' => $unitPrice, // Ensure `unit_price` is provided
            ]);
        }

        Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'stripe',
            'amount' => $totalPrice,
            'status' => 'pending',
            'transaction_id' => $sessionId,
        ]);

        $cart->items()->delete();
        $cart->delete();

        // Clear cart session
        session()->forget(['cart', 'cart_total', 'shipping_condition_id', 'applied_promo']);

        return redirect()->route('checkout.confirmation')->with('success', 'Payment was successful');
    }

    public function cancel()
    {
        return redirect()->route('shop')->with('error', 'Payment was cancelled');
    }

    public function confirmation()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You need to login to proceed to checkout');
        }

        $cart = Cart::where('user_id', Auth::id())->with('items.artwork')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        // Retrieve necessary session data
        $cartTotal = session('cart_total', $cart->items->sum(fn($item) => $item->quantity * $item->price));
        $appliedPromo = session('applied_promo', null);
        $discountAmount = session('discount_amount', 0);

        // Fetch available shipping conditions
        $shippingConditions = \App\Models\ShippingCondition::all();

        // Retrieve user billing & shipping address from their profile
        $user = Auth::user();
        $profile = $user->profile;

        $billingAddress = [
            'address'  => session('billing_address', $profile->address ?? 'N/A'),
            'city'     => session('billing_city', $profile->city ?? ''),
            'state'    => session('billing_state', $profile->state ?? ''),
            'country'  => session('billing_country', $profile->country ?? ''),
            'zipcode'  => session('billing_zipcode', $profile->zipcode ?? ''),
        ];

        $useDifferentShipping = session('use_different_shipping', false);
        $shippingAddress = [
            'recipient_name'  => session('recipient_name', $useDifferentShipping ? '' : $user->profile->first_name . ' ' . $user->profile->last_name),
            'recipient_email' => session('recipient_email', $useDifferentShipping ? '' : $user->email),
            'recipient_phone' => session('recipient_phone', $useDifferentShipping ? '' : $user->profile->phone ?? ''),
            'address'         => session('shipping_address', $useDifferentShipping ? '' : $billingAddress['address']),
            'city'            => session('shipping_city', $useDifferentShipping ? '' : $billingAddress['city']),
            'state'           => session('shipping_state', $useDifferentShipping ? '' : $billingAddress['state']),
            'country'         => session('shipping_country', $useDifferentShipping ? '' : $billingAddress['country']),
            'zipcode'         => session('shipping_zipcode', $useDifferentShipping ? '' : $billingAddress['zipcode']),
        ];

        // Shipping details
        $shippingConditionId = session('shipping_condition_id', null);
        $shippingCondition = $shippingConditionId
            ? ShippingCondition::find($shippingConditionId)
            : null;

        $shippingFee = session('shipping_fee', $shippingCondition->fee ?? 0);

        // Calculate final total
        $finalTotal = $cartTotal - $discountAmount + $shippingFee;

        return view('public.checkout.confirmation', [
            'cart' => $cart,
            'cartTotal' => $cartTotal,
            'appliedPromo' => $appliedPromo,
            'discountAmount' => $discountAmount,
            'shippingConditions' => $shippingConditions,
            'billingAddress' => $billingAddress,
            'useDifferentShipping' => $useDifferentShipping,
            'shippingAddress' => $shippingAddress,
            'shippingCondition' => $shippingCondition,
            'shippingFee' => $shippingFee,
            'finalTotal' => $finalTotal,
        ]);
    }

    public function applyPromoCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $promo = \App\Models\Promotion::where('code', $request->code)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$promo) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired promo code.'], 400);
        }

        // Get cart total from session
        $cartTotal = session()->get('cart_total', 0);

        // Calculate discount
        $discount = $promo->discount_percentage ? ($cartTotal * ($promo->discount_percentage / 100)) : $promo->discount_amount;

        // Store applied promo code and discount amount
        session()->put('applied_promo', $request->code);
        session()->put('discount_amount', $discount);

        return response()->json([
            'success' => true,
            'discount' => number_format($discount, 2),
            'message' => 'Promo code applied successfully!',
        ]);
    }

    public function removePromoCode()
    {
        session()->forget(['applied_promo', 'discount_amount']);

        return response()->json([
            'success' => true,
            'message' => 'Promo code removed successfully!'
        ]);
    }

    public function updateShipping(Request $request)
    {
        $request->validate([
            'shipping_id' => 'required|exists:shipping_conditions,id'
        ]);

        $shippingCondition = ShippingCondition::find($request->shipping_id);

        if (!$shippingCondition) {
            return response()->json(['success' => false, 'message' => 'Invalid shipping condition.'], 400);
        }

        session(['shipping_fee' => $shippingCondition->price]);

        return response()->json([
            'success' => true,
            'shipping_fee' => number_format($shippingCondition->fee, 2),
            'message' => 'Shipping condition updated successfully!',
        ]);
    }

    public function storeSession(Request $request)
    {
        $request->validate([
            'final_total' => 'required|numeric|min:0'
        ]);

        session(['final_total' => $request->final_total]);

        return response()->json([
            'success' => true,
            'message' => 'Session updated successfully.',
        ]);
    }
}
