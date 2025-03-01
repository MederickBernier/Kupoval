# LoginController

Namespace: `App\Http\Controllers\Auth`

File: `app/Http/Controllers/Auth/LoginController.php`

## Description

No documentation available

## Methods

### showLoginForm

Display the login form.

**Visibility:** public

**Returns:** `mixed`

---

### login

Handle a login request to the application.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: * @return \Illuminate\Http\RedirectResponse

**Returns:** `mixed`

---

### logout

Log the user out of the application.
This method handles the user logout process. It logs out the authenticated user,
invalidates the session, regenerates the CSRF token, and redirects the user to the home page
with a success message. If an error occurs during the logout process, it logs the error and
returns the user back with an error message.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The HTTP request instance.

**Returns:** `mixed`

---

