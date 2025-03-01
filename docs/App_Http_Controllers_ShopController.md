# ShopController

Namespace: `App\Http\Controllers`

File: `app/Http/Controllers/ShopController.php`

## Description

No documentation available

## Methods

### index

Display the shop index page.
This method attempts to load the shop index view. If an exception occurs,
it logs the error and redirects the user to the home page with an error message.

**Visibility:** public

**Returns:** `mixed`

---

### cart

Display the user's shopping cart.
This method handles the display of the shopping cart for both authenticated users and guests.
For guests, the cart is loaded from the session. For authenticated users, the cart is loaded
from the database along with the associated items and artwork.
Returns the cart view if successful, or redirects to the shop index with an error message if an exception occurs.

**Visibility:** public

**Returns:** `mixed`

---

