<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PendingPayment;
use App\Models\Promotion;
use App\Models\ShippingCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Mail\PaymentReceipt;

class CheckoutController extends Controller
{
    /**
     * Step 1: Create an order and pending payment.
     */
    private function createOrder()
    {
        $cart = Cart::where('user_id', Auth::id())->with('items.artwork')->first();
        if (!$cart) {
            return redirect()->route('shop')->with('error', 'Your cart is empty');
        }

        $cartTotal = $cart->items->sum(fn($item) => $item->quantity * $item->price);
        $finalTotal = max($cartTotal - session('discount_amount', 0) + session('shipping_fee', 0), 0);

        $order = Order::create([
            'user_id' => Auth::id(),
            'shipping_condition_id' => session('shipping_condition_id', null),
            'status' => 'pending',
            'total' => $finalTotal,
            'billing_address_id' => Auth::user()->profile->billingAddress->id ?? null,
            'shipping_address_id' => session('shipping_address_id', null),
            'recipient_name' => Auth::user()->name,
            'recipient_email' => Auth::user()->email,
        ]);

        Log::info("✅ Order Created: Order #{$order->id}");

        // ✅ Store Order Items Before Creating Stripe Checkout
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'artwork_id' => $item->artwork_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->price,
            ]);
        }

        // ✅ Store a Pending Payment record (for tracking until Stripe confirms)
        PendingPayment::create([
            'order_id' => $order->id,
            'transaction_id' => 'pending', // Will be updated after Stripe checkout
            'amount' => $order->total,
            'status' => 'pending',
        ]);

        return $order;
    }

    /**
     * Step 2: Create Stripe Checkout Session.
     */
    public function createCheckoutSession()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You need to login to proceed to checkout');
        }

        $order = $this->createOrder();

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $lineItems = OrderItem::where('order_id', $order->id)->get()->map(fn($item) => [
                'price_data' => [
                    'currency' => 'cad',
                    'product_data' => ['name' => $item->artwork->name],
                    'unit_amount' => max((int) round($item->unit_price * 100), 50),
                ],
                'quantity' => $item->quantity,
            ])->toArray();

            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),
                'customer_email' => Auth::user()->email,
                'metadata' => ['order_id' => (string) $order->id],
            ]);

            // ✅ Ensure session ID is saved in the order
            $order->stripe_session_id = $checkoutSession->id;
            $order->save(); // ✅ Save explicitly

            Log::info("✅ Stripe Checkout Session Created: {$checkoutSession->id} for Order #{$order->id}");
            return redirect($checkoutSession->url);
        } catch (\Exception $e) {
            Log::error('❌ Stripe Error:', ['message' => $e->getMessage()]);
            return redirect()->route('checkout.confirmation')->with('error', 'Payment could not be processed.');
        }
    }

    /**
     * Step 3: Handle checkout success (POST-Stripe).
     */
    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()->route('shop.index')->with('error', 'Payment failed');
        }

        $order = Order::where('stripe_session_id', $sessionId)->first();
        if (!$order) {
            Log::error("❌ No order found for session ID: {$sessionId}");
            return redirect()->route('shop.index')->with('error', 'Order not found.');
        }

        // Update order status and store final payment
        $order->update(['status' => 'processing']);
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'stripe',
            'amount' => $order->total,
            'status' => 'successful',
            'transaction_id' => $sessionId,
        ]);

        // Remove cart
        Cart::where('user_id', Auth::id())->delete();

        // Send confirmation email
        try {
            Mail::to($order->recipient_email)->send(new OrderConfirmation($order));
        } catch (\Exception $e) {
            Log::error("❌ Failed to send order confirmation email: " . $e->getMessage());
        }

        // Send payment receipt email
        try {
            Mail::to($order->recipient_email)->send(new PaymentReceipt($order, $payment));
        } catch (\Exception $e) {
            Log::error("❌ Failed to send payment receipt email: " . $e->getMessage());
        }

        return redirect()->route('checkout.confirmation')->with('success', 'Payment successful');
    }

    /**
     * Apply a promo code to the cart.
     */
    public function applyPromoCode(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $promoCode = Promotion::where('code', $request->input('code'))
            ->where('is_active', true)
            ->first();

        if (!$promoCode) {
            return response()->json(['success' => false, 'message' => 'Invalid or Expired promo code.'], 400);
        }

        session(['discount_amount' => $promoCode->discount_amount]);
        return response()->json(['success' => true, 'message' => 'Promo code applied!']);
    }

    /**
     * Store session data for checkout.
     */
    public function storeSession(Request $request)
    {
        session([
            'final_total' => $request->final_total,
            'shipping_address' => $request->shipping_address ?? session('shipping_address', [])
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Update shipping information during checkout.
     */
    public function updateShipping(Request $request)
    {
        $shippingCondition = ShippingCondition::find($request->shipping_id);
        if (!$shippingCondition) {
            return response()->json(['success' => false, 'message' => 'Invalid shipping option.'], 400);
        }

        session(['shipping_condition_id' => $shippingCondition->id]);
        return response()->json(['success' => true, 'shipping_fee' => $shippingCondition->fee]);
    }

    public function confirmation()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You need to login to proceed to checkout');
        }

        $cart = Cart::where('user_id', Auth::id())->with('items.artwork')->first();
        if (!$cart) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty.');
        }

        $profile = Auth::user()->profile;
        $billingAddress = [
            'address' => session('billing_address', $profile->billingAddress->address ?? 'N/A'),
            'city' => session('billing_city', $profile->billingAddress->city ?? ''),
            'state' => session('billing_state', $profile->billingAddress->state ?? ''),
            'country' => session('billing_country', $profile->billingAddress->country ?? ''),
            'zipcode' => session('billing_zipcode', $profile->billingAddress->zipcode ?? ''),
        ];

        $useDifferentShipping = session('use_different_shipping', false);
        $shippingAddress = $useDifferentShipping ? $profile->shippingAddresses->first() : $profile->billingAddress;
        $shippingAddress = [
            'address' => session('shipping_address', $shippingAddress->address ?? 'N/A'),
            'city' => session('shipping_city', $shippingAddress->city ?? ''),
            'state' => session('shipping_state', $shippingAddress->state ?? ''),
            'country' => session('shipping_country', $shippingAddress->country ?? ''),
            'zipcode' => session('shipping_zipcode', $shippingAddress->zipcode ?? ''),
        ];

        return view('public.checkout.confirmation', [
            'cart' => $cart,
            'cartTotal' => session('cart_total', $cart->items->sum(fn($item) => $item->quantity * $item->price)),
            'shippingConditions' => ShippingCondition::all(),
            'billingAddress' => $billingAddress,
            'shippingAddress' => $shippingAddress,
            'finalTotal' => max(session('cart_total', 0) - session('discount_amount', 0) + session('shipping_fee', 0), 0),
        ]);
    }
}
