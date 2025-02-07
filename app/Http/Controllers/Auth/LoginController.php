<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    private const ALLOWED_LANGUAGES = ['enca', 'frca'];

    public function showLoginForm()
    {
        return view('public.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                $request->session()->regenerate();

                $lang = $user->profile->language ?? config('app.locale');

                // Vérifier si la langue est valide
                if (!in_array($lang, self::ALLOWED_LANGUAGES)) {
                    $lang = config('app.locale');
                }

                Session::put('locale', $lang);
                Cookie::queue('locale', $lang, 60 * 24 * 30);

                return redirect()->intended(route('user.profile'))
                    ->with('success', __('Login successful.'));
            }

            return back()->withInput()->withErrors([
                'email' => __('The provided credentials do not match our records.'),
            ]);
        } catch (\Exception $e) {
            throwError(__('Login failed. Please try again later.'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('home');
        } catch (\Exception $e) {
            throwError(__('Logout failed. Please try again later.'), 500, ['details' => $e->getMessage()]);
        }
    }
}
