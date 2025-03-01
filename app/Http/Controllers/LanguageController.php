<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class LanguageController extends Controller
{
    private const ALLOWED_LANGUAGES = ['enca', 'frca'];

    /**
     * Switches the application's language based on user input.
     *
     * This method handles the language switching functionality. It retrieves the desired language
     * from the request, validates it against the allowed languages, and updates the session, cookie,
     * and application locale accordingly. If the user is authenticated, it also updates the user's
     * profile with the new language preference.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request containing the language selection.
     * @return \Illuminate\Http\RedirectResponse A redirect response back to the previous page with a status message.
     */
    public function switch(Request $request)
    {
        try {
            $lang = $request->input('languageSwitcher') ?? $request->input('lang');

            if (!in_array($lang, self::ALLOWED_LANGUAGES)) {
                Log::warning("Invalid language selection attempted: {$lang}");
                return back()->with('error', __('Invalid language selection.'));
            }

            $currentLang = Session::get('locale', config('app.locale'));

            if ($currentLang !== $lang) {
                Session::put('locale', $lang);
                Cookie::queue('locale', $lang, 60 * 24 * 30);
                App::setLocale($lang);

                if (Auth::check() && Auth::user()->profile?->language !== $lang) {
                    try {
                        Auth::user()->profile?->update(['language' => $lang]);
                        Log::info("User language updated: User ID: " . Auth::id() . " | Language: {$lang}");
                    } catch (QueryException $dbException) {
                        Log::error("Database error updating language: " . $dbException->getMessage());
                        return back()->with('warning', __('Language switched, but failed to update profile.'));
                    }
                }
            }

            return back()->with('success', __('Language updated successfully.'));
        } catch (\Throwable $e) {
            Log::error("Failed to switch language: " . $e->getMessage());
            return back()->with('error', __('Failed to switch language.'));
        }
    }
}
