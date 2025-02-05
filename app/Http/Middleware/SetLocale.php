<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Définir la langue à partir de la session (ou valeur par défaut)
        $locale = Session::get('locale', config('app.locale'));

        // Appliquer la langue
        App::setLocale($locale);

        return $next($request);
    }
}
