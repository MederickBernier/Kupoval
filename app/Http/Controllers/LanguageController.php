<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    private const ALLOWED_LANGUAGES = ['enca', 'frca'];

    /**
     * Handle language switch request.
     */
    public function switch(Request $request)
    {
        try {
            $lang = $request->input('languageSwitcher') ?? $request->input('lang');

            if (!in_array($lang, self::ALLOWED_LANGUAGES)) {
                return back()->with('error', __('Invalid language selection.'));
            }

            $currentLang = Session::get('locale', config('app.locale'));

            if ($currentLang !== $lang) {
                // If the user is authenticated, update their preferred language in the profile
                if (Auth::check() && Auth::user()->profile?->language !== $lang) {
                    Auth::user()->profile?->update(['language' => $lang]);
                }

                // Store language preference
                Session::put('locale', $lang);
                Cookie::queue('locale', $lang, 60 * 24 * 30); // Store for 30 days
                App::setLocale($lang);
            }

            return back()->with('success', __('Language updated successfully.'));
        } catch (\Exception $e) {
            Log::error('Failed to switch language: ' . $e->getMessage());
            return back()->with('error', __('Failed to switch language.'));
        }
    }
}
