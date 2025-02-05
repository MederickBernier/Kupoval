<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
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
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $request->session()->regenerate();

                return redirect()->intended(route('user.profile'))
                    ->with('success', __('Login successful.'));
            }

            return back()->withErrors([
                'email' => __('The provided credentials do not match our records.'),
            ]);
        } catch (\Exception $e) {
            Log::error("❌ Login failed: " . $e->getMessage());
            return back()->withErrors([
                'error' => __('Login failed. Please try again later.'),
            ]);
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
            throwError(__('Logout failed. Please try again later.'), 500, ['exception' => $e->getMessage()]);
        }
    }
}
