# StripeWebhookController

Namespace: `App\Http\Controllers\Webhooks`

File: `app/Http/Controllers/Webhooks/StripeWebhookController.php`

## Description

No documentation available

## Methods

### handleWebhook

Handle incoming Stripe webhook events.
This method processes the incoming Stripe webhook events by verifying the signature,
parsing the event, and handling specific event types such as:
- checkout.session.completed
- payment_intent.succeeded
- charge.refunded
Logs are created for received events, errors, and unhandled event types.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\Http\JsonResponse

**Returns:** `mixed`

---

### handleCheckoutCompleted

Handle the Stripe checkout session completion.
This method processes the completed Stripe checkout session by verifying the session ID and order ID,
updating the order status, and processing any pending payments associated with the order.

**Visibility:** private

**Parameters:**

- `mixed $session`: The Stripe checkout session object.

**Returns:** `mixed`

---

### handlePaymentSucceeded

Handles the Stripe payment succeeded webhook event.

**Visibility:** private

**Parameters:**

- `mixed $paymentIntent`: The payment intent object from Stripe.

**Returns:** `mixed`

---

### handleChargeRefunded

Handle the Stripe charge refunded event.
This method processes the refund event from Stripe, updates the payment status to 'refunded',
and cancels the associated order if it is not already canceled.

**Visibility:** private

**Parameters:**

- `mixed $charge`: The Stripe charge object containing refund details.

**Returns:** `mixed`

---

