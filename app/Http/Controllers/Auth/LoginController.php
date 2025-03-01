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
     * Display the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('public.auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                $request->session()->regenerate();

                $lang = optional($user->profile)->language ?? config('app.locale');

                if (!in_array($lang, self::ALLOWED_LANGUAGES)) {
                    $lang = config('app.locale');
                }

                Session::put('locale', $lang);
                Cookie::queue('locale', $lang, 60 * 24 * 30);

                Log::info("✅ User logged in successfully: {$user->email}");

                return redirect()->intended(route('user.profile'))
                    ->with('success', __('Login successful.'));
            }

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
     * Log the user out of the application.
     *
     * This method handles the user logout process. It logs out the authenticated user,
     * invalidates the session, regenerates the CSRF token, and redirects the user to the home page
     * with a success message. If an error occurs during the logout process, it logs the error and
     * returns the user back with an error message.
     *
     * @param \Illuminate\Http\Request $request The HTTP request instance.
     * @return \Illuminate\Http\RedirectResponse The response after logout.
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
