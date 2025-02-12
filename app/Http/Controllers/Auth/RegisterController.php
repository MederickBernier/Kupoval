<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('public.auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username',
                'regex:/^[a-zA-Z0-9_]+$/' // Only letters, numbers, and underscores
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[!$%^&*()_+|~=`{}[:;<>?,.@#\]-]).{8,}$/'
            ],
        ], [
            'password.regex' => __('The password must contain at least one letter, one number, and one special character.'),
            'username.regex' => __('The username may only contain letters, numbers, and underscores.'),
        ]);

        try {
            // Create user with explicit field selection to avoid mass assignment issues
            $user = User::create([
                'email'    => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);

            // Attempt to send email verification
            try {
                $user->sendEmailVerificationNotification();
            } catch (\Exception $e) {
                Log::error('Email verification failed: ' . $e->getMessage());
            }

            // Auto-login the user after successful registration
            Auth::login($user);

            return redirect()->route('user.profile')->with('success', __('Registration successful. Please verify your email address.'));
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());

            return back()->withErrors([
                'error' => __('Registration failed. Please try again later.'),
            ]);
        }
    }
}
