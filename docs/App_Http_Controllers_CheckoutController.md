# CheckoutController

Namespace: `App\Http\Controllers`

File: `app/Http/Controllers/CheckoutController.php`

## Description

No documentation available

## Methods

### createOrder

Creates a new order for the authenticated user.
This method retrieves the user's cart, calculates the total amount,
applies any discounts and shipping fees, and creates a new order
with the corresponding order items and pending payment.
Redirects to the shop route with an error message if the cart is empty
or if an exception occurs during order creation. Otherwise, returns the created order.

**Visibility:** private

**Returns:** `mixed`

---

### createCheckoutSession

Create a Stripe checkout session for the authenticated user.
This method checks if the user is authenticated. If not, it redirects to the login page with an error message.
If the user is authenticated, it creates an order and calculates the final total.
It then creates line items for the Stripe checkout session based on the cart items.
A Stripe checkout session is created with the specified payment method types, line items, success and cancel URLs, locale, customer email, and metadata.
The Stripe session ID is saved to the order, and the user is redirected to the Stripe checkout URL.
If an exception occurs during the process, the transaction is rolled back, an error is logged, and the user is redirected to the checkout confirmation page with an error message.

**Visibility:** public

**Returns:** `mixed`

---

### success

Handle the successful payment process.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### applyPromoCode

Apply a promotional code to the user's cart.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The HTTP request instance containing the promo code.

**Returns:** `mixed`

---

### storeSession

Store the final total and shipping address in the session.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The incoming request instance.

**Returns:** `mixed`

---

### updateShipping

Update the shipping information in the session based on the provided shipping ID.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The HTTP request object containing the shipping ID.

**Returns:** `mixed`

---

### confirmation

Display the checkout confirmation page.
This method checks if the user is authenticated. If not, it redirects to the login page.
It then retrieves the user's cart and profile information, including billing and shipping addresses.
Finally, it calculates the cart total and final total, and returns the checkout confirmation view.

**Visibility:** public

**Returns:** `mixed`

---

### calculateFinalTotal

Calculate the final total amount for the user's cart.
This method retrieves the user's cart, calculates the subtotal, applies any promotional discounts,
adds the shipping amount, and then calculates the final total. The calculated values are stored
in the session for later use.

**Visibility:** private

**Returns:** `mixed`

---

### removePromoCode

Remove the applied promo code from the session.
This method forgets the 'promo', 'discount_amount', and 'applied_promo'
session variables, effectively removing any applied promo code and its
associated discount.
A JSON response indicating the success of the operation, a message,
and the final total after removing the promo code.

**Visibility:** public

**Returns:** `mixed`

---

