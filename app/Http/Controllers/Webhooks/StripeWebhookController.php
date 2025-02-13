<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{

    public function handleWebhook(Request $request)
    {
        $payload = $request->all();
        $eventType = $payload['type'] ?? null;

        if ($eventType === 'checkout.session.completed') {
            // Fetch the first hardcoded session ID from the database
            $testSessionId = Order::whereNotNull('stripe_session_id')->value('stripe_session_id');

            if ($testSessionId === $payload['data']['object']['id']) {
                Log::info("✅ Fake Stripe session processed: {$testSessionId}");

                // Simulate Stripe session object
                $fakeSession = (object) [
                    'id' => $testSessionId,
                    'payment_intent' => 'pi_fake_123456',
                    'amount_total' => 30000,
                    'currency' => 'usd',
                ];

                $this->handleCheckoutCompleted($fakeSession);
            } else {
                Log::warning("⚠️ Webhook received but session ID doesn't match any existing order.");
            }
        }

        return response()->json(['status' => 'success']);
    }

    private function handleCheckoutCompleted($session)
    {
        $order = Order::where('stripe_session_id', $session->id)->first();

        if (!$order) {
            Log::error("⚠️ Order not found for session ID: " . $session->id);
            return;
        }

        $order->update(['status' => 'completed']);

        Payment::create([
            'order_id' => $order->id,
            'payment_intent' => $session->payment_intent,
            'amount' => $session->amount_total / 100,
            'currency' => $session->currency,
            'status' => 'successful',
        ]);

        Log::info("✅ Order #{$order->id} marked as paid.");
    }
}
