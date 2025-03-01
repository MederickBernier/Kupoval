# LanguageController

Namespace: `App\Http\Controllers`

File: `app/Http/Controllers/LanguageController.php`

## Description

No documentation available

## Methods

### switch

Switches the application's language based on user input.
This method handles the language switching functionality. It retrieves the desired language
from the request, validates it against the allowed languages, and updates the session, cookie,
and application locale accordingly. If the user is authenticated, it also updates the user's
profile with the new language preference.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: The incoming HTTP request containing the language selection.

**Returns:** `mixed`

---

