<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Mail\PaymentReceiptMail;
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
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    /**
     * Creates a new order for the authenticated user.
     *
     * This method retrieves the user's cart, calculates the total amount,
     * applies any discounts and shipping fees, and creates a new order
     * with the corresponding order items and pending payment.
     *
     * @return \Illuminate\Http\RedirectResponse|\App\Models\Order
     *         Redirects to the shop route with an error message if the cart is empty
     *         or if an exception occurs during order creation. Otherwise, returns the created order.
     */
    private function createOrder()
    {
        try {
            $cart = Cart::where('user_id', Auth::id())->with('items.artwork')->first();

            if (!$cart) {
                return redirect()->route('shop')->with('error', __('Your cart is empty.'));
            }

            $cartTotal = $cart->items->sum(fn($item) => $item->quantity * $item->price);
            $finalTotal = max($cartTotal - session('discount_amount', 0) + session('shipping_fee', 0), 0);
            $promo = session('promo', ['code' => '', 'percent' => 0, 'amount' => 0]);

            $order = Order::create([
                'user_id' => Auth::id(),
                'shipping_condition_id' => session('shipping_condition_id', null),
                'status' => 'pending',
                'total' => $finalTotal,
                'billing_address_id' => Auth::user()->profile->billingAddress->id ?? null,
                'shipping_address_id' => session('shipping_address_id', null),
                'recipient_name' => Auth::user()->name,
                'recipient_email' => Auth::user()->email,
                'promo_code' => $promo['code'],
                'promo_percent' => $promo['percent'],
                'promo_discount' => $promo['amount'],
            ]);

            Log::info("✅ Order Created: Order #{$order->id}");

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'artwork_id' => $item->artwork_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                ]);
            }

            PendingPayment::create([
                'order_id' => $order->id,
                'transaction_id' => 'pending',
                'amount' => $order->total,
                'status' => 'pending',
            ]);

            return $order;
        } catch (\Exception $e) {
            Log::error("❌ Failed to create order: " . $e->getMessage());
            return redirect()->route('shop')->with('error', __('Failed to create order.'));
        }
    }

    /**
     * Create a Stripe checkout session for the authenticated user.
     *
     * This method checks if the user is authenticated. If not, it redirects to the login page with an error message.
     * If the user is authenticated, it creates an order and calculates the final total.
     * It then creates line items for the Stripe checkout session based on the cart items.
     * A Stripe checkout session is created with the specified payment method types, line items, success and cancel URLs, locale, customer email, and metadata.
     * The Stripe session ID is saved to the order, and the user is redirected to the Stripe checkout URL.
     * If an exception occurs during the process, the transaction is rolled back, an error is logged, and the user is redirected to the checkout confirmation page with an error message.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createCheckoutSession()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', __('You need to log in to proceed to checkout.'));
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            DB::beginTransaction();
            $order = $this->createOrder();
            $finalTotal = $this->calculateFinalTotal();
            $cartItems = OrderItem::where('order_id', $order->id)->get();

            $lineItems = [];
            foreach ($cartItems as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'cad',
                        'product_data' => ['name' => $item->artwork->name],
                        'unit_amount' => max((int) round($item->unit_price * 100), 50),
                    ],
                    'quantity' => $item->quantity,
                ];
            }

            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),
                'locale' => mapStripeLocale(App::getLocale()),
                'customer_email' => Auth::user()->email,
                'metadata' => ['order_id' => (string) $order->id],
            ]);

            $order->stripe_session_id = $checkoutSession->id;
            $order->save();

            DB::commit();
            return redirect($checkoutSession->url);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("❌ Stripe Error: " . $e->getMessage());
            return redirect()->route('checkout.confirmation')->with('error', __('Payment could not be processed.'));
        }
    }

    /**
     * Handle the successful payment process.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Exception
     */
    public function success(Request $request)
    {
        try {
            $sessionId = $request->query('session_id');

            if (!$sessionId) {
                return redirect()->route('shop.index')->with('error', __('Payment failed.'));
            }

            $order = Order::where('stripe_session_id', $sessionId)->firstOrFail();
            $order->update(['status' => 'processing']);

            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'stripe',
                'amount' => $order->total,
                'status' => 'successful',
                'transaction_id' => $sessionId,
            ]);

            Cart::where('user_id', Auth::id())->delete();

            try {
                Mail::to($order->recipient_email)->send(new OrderConfirmationMail($order));
                Mail::to($order->recipient_email)->send(new PaymentReceiptMail($order, $payment));
            } catch (\Exception $e) {
                Log::error("❌ Failed to send email: " . $e->getMessage());
            }

            return redirect()->route('checkout.confirmation')->with('success', __('Payment successful.'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('shop.index')->with('error', __('Order not found.'));
        } catch (\Exception $e) {
            Log::error("❌ Payment success handling failed: " . $e->getMessage());
            return redirect()->route('shop.index')->with('error', __('An error occurred while processing your payment.'));
        }
    }

    /**
     * Apply a promotional code to the user's cart.
     *
     * @param \Illuminate\Http\Request $request The HTTP request instance containing the promo code.
     * @return \Illuminate\Http\JsonResponse JSON response indicating success or failure of applying the promo code.
     *
     * @throws \Illuminate\Validation\ValidationException If the promo code format is invalid.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the promo code is invalid or expired.
     * @throws \Exception If an error occurs while applying the promo code.
     */
    public function applyPromoCode(Request $request)
    {
        try {
            $request->validate(['code' => 'required|string']);

            $promoCode = Promotion::where('code', $request->input('code'))->where('is_active', true)->firstOrFail();

            session([
                'promo' => [
                    'code' => $promoCode->code,
                    'percent' => $promoCode->discount_percentage,
                    'amount' => round(Cart::where('user_id', Auth::id())->first()->items->sum(fn($item) => $item->quantity * $item->price) * ($promoCode->discount_percentage / 100), 2),
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => __('Promo code applied!'),
                'final_total' => $this->calculateFinalTotal(),
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'message' => __('Invalid promo code format.')], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => __('Invalid or expired promo code.')]);
        } catch (\Exception $e) {
            Log::error("❌ Failed to apply promo code: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => __('An error occurred while applying the promo code.')]);
        }
    }

    /**
     * Store the final total and shipping address in the session.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response indicating success.
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
     * Update the shipping information in the session based on the provided shipping ID.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing the shipping ID.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating success or failure, along with the shipping fee and final total.
     */
    public function updateShipping(Request $request)
    {
        $shippingCondition = ShippingCondition::find($request->shipping_id);
        if (!$shippingCondition) return response()->json(['success' => false, 'message' => 'Invalid shipping condition']);

        session([
            'shippingAmount' => $shippingCondition->fee,
            'shipping_condition_id' => $shippingCondition->id,
            'total' => $this->calculateFinalTotal(),
        ]);

        return response()->json([
            'success' => true,
            'shipping_fee' => $shippingCondition->fee,
            'final_total' => $this->calculateFinalTotal()
        ]);
    }

    /**
     * Display the checkout confirmation page.
     *
     * This method checks if the user is authenticated. If not, it redirects to the login page.
     * It then retrieves the user's cart and profile information, including billing and shipping addresses.
     * Finally, it calculates the cart total and final total, and returns the checkout confirmation view.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
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
            'finalTotal' => max(session('subtotal', 0) - session('promo.amount', 0) + session('shippingAmount', 0), 0),
        ]);
    }

    /**
     * Calculate the final total amount for the user's cart.
     *
     * This method retrieves the user's cart, calculates the subtotal, applies any promotional discounts,
     * adds the shipping amount, and then calculates the final total. The calculated values are stored
     * in the session for later use.
     *
     * @return float The final total amount for the user's cart.
     */
    private function calculateFinalTotal()
    {
        $cart = Cart::where('user_id', Auth::id())->with('items.artwork')->first();
        if (!$cart) return 0;

        $subtotal = $cart->items->sum(fn($item) => $item->quantity * $item->price);
        $promo = session('promo', ['code' => '', 'percent' => 0, 'amount' => 0]);
        $shippingAmount = session('shippingAmount', 0);

        $discountAmount = ($promo['percent'] > 0) ? round($subtotal * ($promo['percent'] / 100), 2) : 0;

        $promo['amount'] = $discountAmount;

        $finalTotal = max($subtotal - $discountAmount + $shippingAmount, 0);

        session([
            'subtotal' => $subtotal,
            'promo' => $promo,
            'shippingAmount' => $shippingAmount,
            'total' => $finalTotal
        ]);

        return $finalTotal;
    }

    /**
     * Remove the applied promo code from the session.
     *
     * This method forgets the 'promo', 'discount_amount', and 'applied_promo'
     * session variables, effectively removing any applied promo code and its
     * associated discount.
     *
     * @return \Illuminate\Http\JsonResponse
     *   A JSON response indicating the success of the operation, a message,
     *   and the final total after removing the promo code.
     */
    public function removePromoCode()
    {
        session()->forget('promo');
        session()->forget('discount_amount');
        session()->forget('applied_promo');

        return response()->json([
            'success' => true,
            'message' => 'Promo code removed',
            'final_total' => $this->calculateFinalTotal()
        ]);
    }
}
