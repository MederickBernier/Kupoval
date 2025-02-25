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
     * Handle language switch request.
     */
    public function switch(Request $request)
    {
        try {
            $lang = $request->input('languageSwitcher') ?? $request->input('lang');

            // 🔹 Validate Language Selection
            if (!in_array($lang, self::ALLOWED_LANGUAGES)) {
                Log::warning("⚠️ Invalid language selection attempted: {$lang}");
                return back()->with('error', __('Invalid language selection.'));
            }

            // 🔹 Get current language from session (default to app locale)
            $currentLang = Session::get('locale', config('app.locale'));

            if ($currentLang !== $lang) {
                // 🔹 Store language preference for guests & users
                Session::put('locale', $lang);
                Cookie::queue('locale', $lang, 60 * 24 * 30); // Store for 30 days
                App::setLocale($lang);

                // 🔹 If authenticated, update user's profile language
                if (Auth::check() && Auth::user()->profile?->language !== $lang) {
                    try {
                        Auth::user()->profile?->update(['language' => $lang]);
                        Log::info("✅ User language updated: User ID: " . Auth::id() . " | Language: {$lang}");
                    } catch (QueryException $dbException) {
                        Log::error("❌ Database error updating language: " . $dbException->getMessage());
                        return back()->with('warning', __('Language switched, but failed to update profile.'));
                    }
                }
            }

            return back()->with('success', __('Language updated successfully.'));
        } catch (\Throwable $e) {
            Log::error("❌ Failed to switch language: " . $e->getMessage());
            return back()->with('error', __('Failed to switch language.'));
        }
    }
}
