<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware to set the application's locale based on various sources.
 *
 * This middleware checks for the locale in the following order:
 * 1. Session
 * 2. Authenticated user's profile
 * 3. Cookie
 * 4. Application default configuration
 *
 * Once the locale is determined, it sets the application's locale and Carbon's locale,
 * and stores the locale in the session.
 *
 * @param \Illuminate\Http\Request $request The incoming request instance.
 * @param \Closure $next The next middleware to call.
 * @return mixed The response from the next middleware.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = Session::get('locale');

        if (!$locale && Auth::check() && Auth::user()->profile) {
            $locale = Auth::user()->profile->language;
        }

        if (!$locale) {
            $locale = Cookie::get('locale');
        }

        if (!$locale) {
            $locale = config('app.locale', 'frca');
        }

        App::setLocale($locale);
        Carbon::setLocale($locale);

        Session::put('locale', $locale);

        return $next($request);
    }
}
