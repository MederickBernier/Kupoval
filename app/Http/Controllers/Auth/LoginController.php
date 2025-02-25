<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Throwable;
use Exception;

class LoginController extends Controller
{
    private const ALLOWED_LANGUAGES = ['enca', 'frca'];

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('public.auth.login');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        // ✅ Validate Input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                $request->session()->regenerate();

                // ✅ Ensure user has a profile before accessing language
                $lang = optional($user->profile)->language ?? config('app.locale');

                // ✅ Verify language is allowed
                if (!in_array($lang, self::ALLOWED_LANGUAGES)) {
                    $lang = config('app.locale');
                }

                // ✅ Set session and cookie for localization
                Session::put('locale', $lang);
                Cookie::queue('locale', $lang, 60 * 24 * 30); // 30 days

                Log::info("✅ User logged in successfully: {$user->email}");

                return redirect()->intended(route('user.profile'))
                    ->with('success', __('Login successful.'));
            }

            // 🚨 Log failed attempts for security
            Log::warning("⚠️ Failed login attempt for email: {$request->email}");

            return back()->withInput()->withErrors([
                'email' => __('The provided credentials do not match our records.'),
            ]);
        } catch (Throwable $e) {
            Log::error("❌ Login failed: " . $e->getMessage());

            return back()->withInput()->withErrors([
                'email' => __('Login failed. Please try again later.'),
            ]);
        } finally {
            Log::info("🔍 Login process finalized for email: {$request->email}");
        }
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        try {
            $userEmail = Auth::check() ? Auth::user()->email : 'unknown';

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::info("✅ User logged out: {$userEmail}");

            return redirect()->route('home')->with('success', __('Logged out successfully.'));
        } catch (Exception $e) {
            Log::error("❌ Logout failed: " . $e->getMessage());

            return back()->withErrors([
                'error' => __('Logout failed. Please try again later.'),
            ]);
        } finally {
            Log::info("🔍 Logout process finalized.");
        }
    }
}
