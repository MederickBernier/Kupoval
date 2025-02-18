<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PendingPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $endpointSecret = config('services.stripe.webhook_secret');
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid Stripe Webhook Payload');
            return response()->json(['error' => 'Invalid Payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe Webhook Signature Verification Failed');
            return response()->json(['error' => 'Invalid Signature'], 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutCompleted($event->data->object);
                break;

            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;

            default:
                Log::info("Received unhandled Stripe event: {$event->type}");
                break;
        }

        return response()->json(['status' => 'success']);
    }

    private function handleCheckoutCompleted($session)
    {
        $order = Order::where('stripe_session_id', $session->id)->first();

        if (!$order) {
            Log::error("Order not found for session ID: " . $session->id);
            return;
        }

        $pendingPayment = PendingPayment::where('transaction_id', $session->payment_intent)->first();

        if ($pendingPayment) {
            Log::info("Linking previously unprocessed payment to Order #{$order->id}");

            Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'stripe',
                'amount' => $pendingPayment->amount,
                'status' => 'successful',
                'transaction_id' => $pendingPayment->transaction_id,
            ]);

            $pendingPayment->delete();
        }

        Log::info("Order #{$order->id} successfully processed.");
    }

    private function handlePaymentSucceeded($paymentIntent)
    {
        Log::info("Stripe Payment Succeeded: {$paymentIntent->id}");

        $payment = Payment::where('transaction_id', $paymentIntent->id)->first();

        if (!$payment) {
            Log::warning("Payment record not found for transaction ID: {$paymentIntent->id}. Trying to link to order...");

            $order = Order::where('stripe_session_id', $paymentIntent->metadata->stripe_session_id ?? null)->first();

            if (!$order) {
                Log::error("No order found for payment intent: {$paymentIntent->id}. Storing for later processing.");
                return;
            }

            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'stripe',
                'amount' => ($paymentIntent->amount_received / 100),
                'status' => 'successful',
                'transaction_id' => $paymentIntent->id,
            ]);

            Log::info("Created missing payment record for Order #{$order->id}.");
        } else {
            $payment->update(['status' => 'successful']);
            Log::info("Payment record #{$payment->id} updated to successful.");
        }
    }

    private function handlePaymentFailed($paymentIntent)
    {
        Log::warning("Stripe Payment Failed: {$paymentIntent->id}");

        $payment = Payment::where('transaction_id', $paymentIntent->id)->first();

        if ($payment) {
            $payment->update(['status' => 'failed']);
        }

        $order = $payment ? $payment->order : null;
        if ($order) {
            $order->update(['status' => 'failed']);
            Log::warning("Order #{$order->id} marked as failed.");
        }
    }
}
