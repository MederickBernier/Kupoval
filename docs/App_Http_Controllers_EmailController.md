# EmailController

Namespace: `App\Http\Controllers`

File: `app/Http/Controllers/EmailController.php`

## Description

No documentation available

## Methods

### sendVerificationEmail

Send account verification email to the authenticated user.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### sendPasswordResetEmail

Send password reset email to the user.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### sendOrderConfirmationEmail

Send order confirmation email to the recipient of the order.

**Visibility:** public

**Parameters:**

- `App\Models\Order $order`: * @return \Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### sendPaymentReceiptEmail

Send payment receipt email to the recipient of the order.

**Visibility:** public

**Parameters:**

- `App\Models\Payment $payment`: * @return \Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### sendShippingNotificationEmail

Send shipping notification email to the recipient of the order.

**Visibility:** public

**Parameters:**

- `App\Models\Order $order`: * @return \Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### sendRefundConfirmationEmail

Send refund confirmation email to the recipient of the order.

**Visibility:** public

**Parameters:**

- `App\Models\Payment $payment`: * @return \Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

