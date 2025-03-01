# RegisterController

Namespace: `App\Http\Controllers\Auth`

File: `app/Http/Controllers/Auth/RegisterController.php`

## Description

No documentation available

## Methods

### showRegistrationForm

Display the registration form.

**Visibility:** public

**Returns:** `mixed`

---

### register

Handle the registration request.
Logs the registration attempt, validates the request data, creates a new user and profile,
generates a verification URL, sends a verification email, and handles any errors that occur.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

