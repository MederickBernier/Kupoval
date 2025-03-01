# UserProfileController

Namespace: `App\Http\Controllers`

File: `app/Http/Controllers/UserProfileController.php`

## Description

No documentation available

## Methods

### profile

Display the user's profile page.
This method retrieves the authenticated user's profile information,
including addresses and wishlist items, and displays it on the profile page.
If the user is not authenticated, they are redirected to the login page.
If the user does not have a profile, a new profile is automatically created.
Redirects to the login page if the user is not authenticated,
or to the home page if an error occurs while loading the profile.
Otherwise, returns the profile view with user data.

**Visibility:** public

**Returns:** `mixed`

---

### Address

Update the specified address for the authenticated user.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The request instance containing the address data.
- `mixed $addressId`: The ID of the address to be updated.

**Returns:** `mixed`

---

### updateProfile

Update the user's profile.
This method handles the request to update the authenticated user's profile.
It validates the input data, updates the user's profile, and logs the outcome.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The incoming request containing profile data.

**Returns:** `mixed`

---

### editField

Edit a specific user profile field.
This method retrieves the authenticated user and attempts to load the view
for editing a specific profile field. If the user is not authenticated, it
redirects to the login page with an error message. If an exception occurs
during the process, it logs the error and returns a 500 error response.

**Visibility:** public

**Parameters:**

- `mixed $field`: The profile field to be edited.

**Returns:** `mixed`

---

### updateField

Update a specific field in the user's profile.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The incoming request instance.
- `mixed $field`: The profile field to be updated (must be 'first_name', 'last_name', or 'email').

**Returns:** `mixed`

---

