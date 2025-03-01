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
    /**
     * Handle incoming Stripe webhook events.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \UnexpectedValueException If the payload is invalid.
     * @throws \Stripe\Exception\SignatureVerificationException If the signature verification fails.
     * @throws \Exception If an unknown error occurs.
     *
     * This method processes the incoming Stripe webhook events by verifying the signature,
     * parsing the event, and handling specific event types such as:
     * - checkout.session.completed
     * - payment_intent.succeeded
     * - charge.refunded
     *
     * Logs are created for received events, errors, and unhandled event types.
     */
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $endpointSecret = config('services.stripe.webhook_secret');
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($request->getContent(), $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            Log::error("Stripe Webhook: Invalid payload.", ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid webhook payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error("Stripe Webhook: Signature verification failed", ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid webhook signature'], 400);
        } catch (\Exception $e) {
            Log::error("Stripe Webhook: Unknown error", ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An unknown error occurred'], 500);
        }

        $eventType = $event->type;
        $eventData = $event->data->object;

        Log::info("Stripe Webhook Received: Event Type - $eventType");

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
                    Log::warning("Stripe Webhook: Unhandled event type - $eventType");
            }
        } catch (\Exception $e) {
            Log::error("Stripe Webhook: Error processing event $eventType", ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while processing the webhook event'], 500);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Handle the Stripe checkout session completion.
     *
     * This method processes the completed Stripe checkout session by verifying the session ID and order ID,
     * updating the order status, and processing any pending payments associated with the order.
     *
     * @param \Stripe\Checkout\Session $session The Stripe checkout session object.
     * @return void
     * @throws \InvalidArgumentException If the session ID or order ID is missing.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the order is not found.
     * @throws \Exception If any other error occurs during processing.
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
                Log::warning("Stripe Webhook: Order #{$order->id} is already paid. Skipping.");
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
                Log::info("Stripe Webhook: Processed pending payment for Order #{$order->id}.");
            }
        } catch (ModelNotFoundException $e) {
            Log::error("Stripe Webhook: Order not found", ['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            Log::error("Stripe Webhook: Failed to process checkout completion", ['error' => $e->getMessage()]);
        }
    }

    /**
     * Handles the Stripe payment succeeded webhook event.
     *
     * @param \Stripe\PaymentIntent $paymentIntent The payment intent object from Stripe.
     *
     * @throws \InvalidArgumentException If the transaction ID or order ID is missing.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the order is not found.
     * @throws \Exception If there is any other error during the process.
     *
     * @return void
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
                Log::warning("Stripe Webhook: Duplicate payment detected. Skipping.");
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

            Log::info("Stripe Webhook: Payment recorded for Order #{$order->id}.");
        } catch (ModelNotFoundException $e) {
            Log::error("Stripe Webhook: Order not found", ['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            Log::error("Stripe Webhook: Failed to process payment intent success", ['error' => $e->getMessage()]);
        }
    }

    /**
     * Handle the Stripe charge refunded event.
     *
     * This method processes the refund event from Stripe, updates the payment status to 'refunded',
     * and cancels the associated order if it is not already canceled.
     *
     * @param \Stripe\Charge $charge The Stripe charge object containing refund details.
     *
     * @throws \InvalidArgumentException If the transaction ID is missing.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the payment is not found.
     * @throws \Exception If any other error occurs during processing.
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
            Log::info("Stripe Webhook: Payment ID {$payment->id} marked as REFUNDED.");

            $order = $payment->order;
            if ($order && $order->status !== 'canceled') {
                $order->update(['status' => 'canceled']);
                Log::info("Stripe Webhook: Order #{$order->id} marked as CANCELED due to refund.");
            }
        } catch (ModelNotFoundException $e) {
            Log::error("Stripe Webhook: Payment not found for refund", ['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            Log::error("Stripe Webhook: Failed to process refund", ['error' => $e->getMessage()]);
        }
    }
}
