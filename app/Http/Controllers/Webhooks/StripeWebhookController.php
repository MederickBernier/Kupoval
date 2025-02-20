<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PendingPayment;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        // ✅ Verify Webhook Signature
        $endpointSecret = config('services.stripe.webhook_secret');
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($request->getContent(), $sigHeader, $endpointSecret);
        } catch (\Exception $e) {
            Log::error("❌ Stripe Webhook: Signature verification failed", ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid webhook signature'], 400);
        }

        $eventType = $event->type;
        $eventData = $event->data->object;

        Log::info("🔔 Stripe Webhook Received: Event Type - $eventType");

        switch ($eventType) {
            case 'checkout.session.completed':
                $this->handleCheckoutCompleted($eventData);
                break;

            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($eventData);
                break;

            case 'charge.refunded':
                $this->handleChargeRefunded($eventData);
                break;

            default:
                Log::warning("⚠️ Stripe Webhook: Unhandled event type - $eventType");
        }

        return response()->json(['success' => true]);
    }

    /**
     * ✅ Handles Checkout Session Completion
     * Marks payment as successful and associates it with the order.
     */
    private function handleCheckoutCompleted($session)
    {
        $sessionId = $session->id ?? null;
        $orderId = $session->metadata->order_id ?? null;

        if (!$sessionId || !$orderId) {
            Log::error("❌ Stripe Webhook: Missing session ID or order ID.", [
                'sessionId' => $sessionId,
                'orderId' => $orderId,
                'metadata' => json_encode($session->metadata)
            ]);
            return;
        }

        $order = Order::where('stripe_session_id', $sessionId)->first();
        if (!$order) {
            Log::error("❌ Stripe Webhook: No order found for session ID $sessionId (Order ID: $orderId)");
            return;
        }

        if ($order->status === 'paid') {
            Log::warning("⚠️ Stripe Webhook: Order #{$order->id} is already paid. Skipping duplicate update.");
            return;
        }

        // ✅ Process pending payment
        $pendingPayment = PendingPayment::where('order_id', $order->id)->first();
        if ($pendingPayment) {
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'stripe',
                'amount' => $pendingPayment->amount,
                'status' => 'successful',
                'transaction_id' => $sessionId,
            ]);
            Log::info("✅ Stripe Webhook: Payment recorded from pending payment. Deleting pending record.");
            $pendingPayment->delete();
        }

        Log::info("✅ Stripe Webhook: Payment processed successfully for Order #{$order->id}.");
    }

    /**
     * ✅ Handles Payment Intent Success (Backup Verification)
     * Ensures payment is recorded properly in case checkout session fails to complete.
     */
    private function handlePaymentSucceeded($paymentIntent)
    {
        $transactionId = $paymentIntent->id ?? null;
        $metadata = (array) $paymentIntent->metadata;
        $orderId = $metadata['order_id'] ?? null;

        // ✅ Log metadata for debugging
        Log::info("🔎 Debugging payment_intent.succeeded: ", [
            'transactionId' => $transactionId,
            'orderId' => $orderId,
            'metadata' => json_encode($metadata)
        ]);

        if (!$transactionId || !$orderId) {
            Log::error("❌ Stripe Webhook: Missing transaction ID or order ID in payment intent.");
            return;
        }

        $order = Order::find($orderId);
        if (!$order) {
            Log::error("❌ Stripe Webhook: No order found for payment intent $transactionId (Order ID: $orderId)");
            return;
        }

        $existingPayment = Payment::where('transaction_id', $transactionId)->first();
        if ($existingPayment) {
            Log::warning("⚠️ Stripe Webhook: Payment for transaction ID $transactionId is already recorded. Skipping.");
            return;
        }

        // ✅ Move payment from pending to completed
        $pendingPayment = PendingPayment::where('order_id', $orderId)->first();
        $amount = $pendingPayment ? $pendingPayment->amount : ($paymentIntent->amount_received / 100);

        Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'stripe',
            'amount' => $amount,
            'status' => 'successful',
            'transaction_id' => $transactionId,
        ]);

        // ✅ Delete pending payment if exists
        if ($pendingPayment) {
            Log::info("✅ Stripe Webhook: Payment matched pending entry. Deleting pending payment.");
            $pendingPayment->delete();
        }

        Log::info("✅ Stripe Webhook: Payment recorded for Order #{$order->id}.");
    }

    /**
     * ✅ Handles Refunds
     * Marks payment as refunded and updates order if necessary.
     */
    private function handleChargeRefunded($charge)
    {
        $transactionId = $charge->payment_intent ?? null;

        if (!$transactionId) {
            Log::error("❌ Stripe Webhook: Missing transaction ID for refund.");
            return;
        }

        $payment = Payment::where('transaction_id', $transactionId)->first();
        if (!$payment) {
            Log::error("❌ Stripe Webhook: No payment found for refund transaction ID $transactionId.");
            return;
        }

        // ✅ Mark payment as refunded
        $payment->update(['status' => 'refunded']);
        Log::info("✅ Stripe Webhook: Payment ID {$payment->id} marked as REFUNDED.");

        // ❓ Optional: Should we update order status?
        $order = $payment->order;
        if ($order && $order->status !== 'canceled') {
            $order->update(['status' => 'canceled']);
            Log::info("⚠️ Stripe Webhook: Order #{$order->id} marked as CANCELED due to refund.");
        }
    }
}
