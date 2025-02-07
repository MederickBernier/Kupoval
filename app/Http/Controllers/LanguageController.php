<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    private const ALLOWED_LANGUAGES = ['enca', 'frca'];

    public function switch(Request $request)
    {
        try {
            $lang = $request->input('languageSwitcher') ?? $request->input('lang');

            if (!in_array($lang, self::ALLOWED_LANGUAGES)) {
                return back()->withInput()->with('error', 'Invalid language');
            }

            if ($user = Auth::user()) {
                $user->profile?->update(['language' => $lang]);
            }

            Session::put('locale', $lang);
            Cookie::queue('locale', $lang, 60 * 24 * 30);
            App::setLocale($lang);

            return back();
        } catch (\Exception $e) {
            throwError('Failed to switch language', 500, ['details' => $e->getMessage()]);
        }
    }
}
