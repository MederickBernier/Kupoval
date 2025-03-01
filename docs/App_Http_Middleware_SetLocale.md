# SetLocale

Namespace: `App\Http\Middleware`

File: `app/Http/Middleware/SetLocale.php`

## Description

Middleware to set the application's locale based on various sources.
This middleware checks for the locale in the following order:
1. Session
2. Authenticated user's profile
3. Cookie
4. Application default configuration
Once the locale is determined, it sets the application's locale and Carbon's locale,
and stores the locale in the session.

## Methods

### handle

No documentation available

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: No description available
- `Closure $next`: No description available

**Returns:** `mixed`

---

