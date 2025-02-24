<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // 1️⃣ Check if locale is already set in session
        $locale = Session::get('locale');

        // 2️⃣ If not in session, check for an authenticated user
        if (!$locale && Auth::check() && Auth::user()->profile) {
            $locale = Auth::user()->profile->language;
        }

        // 3️⃣ If not in DB, check the user's cookie
        if (!$locale) {
            $locale = Cookie::get('locale');
        }

        // 4️⃣ Default to the app's config locale if nothing is found
        if (!$locale) {
            $locale = config('app.locale', 'frca'); // Default fallback to fr_CA
        }

        // 5️⃣ Set the locale globally in Laravel & Carbon
        App::setLocale($locale);
        Carbon::setLocale($locale);

        // 6️⃣ Store in session for persistency
        Session::put('locale', $locale);

        return $next($request);
    }
}
