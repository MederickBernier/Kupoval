<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    public function switch(Request $request)
    {
        $lang = $request->input('languageSwitcher') ?? $request->input('lang');

        if (!in_array($lang, ['enca', 'frca'])) {
            return back()->with('error', 'Invalid language');
        }

        if (Auth::check()) {
            $user = Auth::user();
            $profile = $user->profile;

            if ($profile) {
                $profile->update(['language' => $lang]);
            }
        }

        Session::put('locale', $lang);
        Cookie::queue('locale', $lang, 60 * 24 * 30);

        App::setLocale($lang);

        return redirect()->back();
    }
}
