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
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        } catch (\UnexpectedValueException $e) {
            Log::error("❌ Stripe Webhook: Invalid payload.", ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid webhook payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error("❌ Stripe Webhook: Signature verification failed", ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid webhook signature'], 400);
        } catch (\Exception $e) {
            Log::error("❌ Stripe Webhook: Unknown error", ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An unknown error occurred'], 500);
        }

        $eventType = $event->type;
        $eventData = $event->data->object;

        Log::info("🔔 Stripe Webhook Received: Event Type - $eventType");

        try {
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
        } catch (\Exception $e) {
            Log::error("❌ Stripe Webhook: Error processing event $eventType", ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while processing the webhook event'], 500);
        }

        return response()->json(['success' => true]);
    }

    /**
     * ✅ Handles Checkout Session Completion
     */
    private function handleCheckoutCompleted($session)
    {
        try {
            $sessionId = $session->id ?? null;
            $orderId = $session->metadata->order_id ?? null;

            if (!$sessionId || !$orderId) {
                throw new \InvalidArgumentException("Missing session ID or order ID.");
            }

            $order = Order::where('stripe_session_id', $sessionId)->firstOrFail();

            if ($order->status === 'paid') {
                Log::warning("⚠️ Stripe Webhook: Order #{$order->id} is already paid. Skipping.");
                return;
            }

            $pendingPayment = PendingPayment::where('order_id', $order->id)->first();
            if ($pendingPayment) {
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'stripe',
                    'amount' => $pendingPayment->amount,
                    'status' => 'successful',
                    'transaction_id' => $sessionId,
                ]);
                $pendingPayment->delete();
                Log::info("✅ Stripe Webhook: Processed pending payment for Order #{$order->id}.");
            }
        } catch (ModelNotFoundException $e) {
            Log::error("❌ Stripe Webhook: Order not found", ['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            Log::error("❌ Stripe Webhook: Failed to process checkout completion", ['error' => $e->getMessage()]);
        }
    }

    /**
     * ✅ Handles Payment Intent Success (Backup Verification)
     */
    private function handlePaymentSucceeded($paymentIntent)
    {
        try {
            $transactionId = $paymentIntent->id ?? null;
            $metadata = (array) $paymentIntent->metadata;
            $orderId = $metadata['order_id'] ?? null;

            if (!$transactionId || !$orderId) {
                throw new \InvalidArgumentException("Missing transaction ID or order ID.");
            }

            $order = Order::findOrFail($orderId);
            $existingPayment = Payment::where('transaction_id', $transactionId)->first();

            if ($existingPayment) {
                Log::warning("⚠️ Stripe Webhook: Duplicate payment detected. Skipping.");
                return;
            }

            $pendingPayment = PendingPayment::where('order_id', $orderId)->first();
            $amount = $pendingPayment ? $pendingPayment->amount : ($paymentIntent->amount_received / 100);

            Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'stripe',
                'amount' => $amount,
                'status' => 'successful',
                'transaction_id' => $transactionId,
            ]);

            if ($pendingPayment) {
                $pendingPayment->delete();
            }

            Log::info("✅ Stripe Webhook: Payment recorded for Order #{$order->id}.");
        } catch (ModelNotFoundException $e) {
            Log::error("❌ Stripe Webhook: Order not found", ['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            Log::error("❌ Stripe Webhook: Failed to process payment intent success", ['error' => $e->getMessage()]);
        }
    }

    /**
     * ✅ Handles Refunds
     */
    private function handleChargeRefunded($charge)
    {
        try {
            $transactionId = $charge->payment_intent ?? null;

            if (!$transactionId) {
                throw new \InvalidArgumentException("Missing transaction ID for refund.");
            }

            $payment = Payment::where('transaction_id', $transactionId)->firstOrFail();

            $payment->update(['status' => 'refunded']);
            Log::info("✅ Stripe Webhook: Payment ID {$payment->id} marked as REFUNDED.");

            $order = $payment->order;
            if ($order && $order->status !== 'canceled') {
                $order->update(['status' => 'canceled']);
                Log::info("⚠️ Stripe Webhook: Order #{$order->id} marked as CANCELED due to refund.");
            }
        } catch (ModelNotFoundException $e) {
            Log::error("❌ Stripe Webhook: Payment not found for refund", ['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            Log::error("❌ Stripe Webhook: Failed to process refund", ['error' => $e->getMessage()]);
        }
    }
}
