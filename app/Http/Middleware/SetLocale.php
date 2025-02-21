<?php

namespace App\Http\Middleware;

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
        $locale = null;

        if (Auth::check()) {
            $profile = Auth::user()->profile;
            $locale = $profile ? $profile->language : 'frca';
        }

        if (!$locale) {
            $locale = Cookie::get('locale');
        }

        if (!$locale) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);
        Session::put('locale', $locale);
        \Carbon\Carbon::setLocale(Session::get('locale'));

        return $next($request);
    }
}
