<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountVerificationMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Exception;

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
        Log::info('📩 Registration attempt', ['email' => $request->email]);

        // ✅ Validate Input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            Log::warning('⚠️ Registration validation failed', ['errors' => $validator->errors()->toArray()]);
            return back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            // ✅ Create user
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Log::info("✅ User created: {$user->email} (ID: {$user->id})");

            // ✅ Ensure the user has a profile
            $user->profile()->create([
                'first_name' => '',
                'last_name' => '',
                'title' => '',
            ]);
            Log::info("✅ Profile created for user ID: {$user->id}");

            // ✅ Generate verification URL
            $verificationUrl = URL::signedRoute(
                'verification.verify',
                ['id' => $user->id, 'hash' => sha1($user->email)],
                Carbon::now()->addMinutes(60)
            );

            Log::info("🔗 Verification URL generated for {$user->email}");

            // ✅ Send verification email
            Mail::to($user->email)->send(new AccountVerificationMail($user, $verificationUrl));
            Log::info("📧 Verification email sent to: {$user->email}");

            DB::commit();
            Log::info("✅ Registration completed successfully for: {$user->email}");

            // ✅ Redirect with success message
            return redirect()->route('user.profile')->with('success', __('Registration successful. Please check your email to verify your account.'));
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof \Illuminate\Database\QueryException) {
                Log::error("❌ Database error during registration: " . $e->getMessage());
                return back()->withInput()->with('error', __('A database error occurred. Please try again.'));
            } else {
                Log::error("❌ Unexpected error during registration: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                return back()->withInput()->with('error', __('Registration failed. Please try again.'));
            }
        } finally {
            Log::info("🔍 Registration process finalized for email: {$request->email}");
        }
    }
}
