# LoadSocialLinks

Namespace: `App\Http\Middleware`

File: `app/Http/Middleware/LoadSocialLinks.php`

## Description

Middleware to load social links into the session if they are not already present.
This middleware checks if the session has the 'social_links' key. If not, it retrieves
the social media links from the settings table and stores them in the session.

## Methods

### handle

Handle an incoming request.

**Visibility:** public

**Parameters:**

- `Illuminate\Http\Request $request`: No description available
- `Closure $next`: No description available

**Returns:** `Symfony\Component\HttpFoundation\Response`

---

