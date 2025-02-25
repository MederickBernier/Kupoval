<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Verified;
use Exception;

class VerificationController extends Controller
{
    /**
     * Show the email verification notice page.
     */
    public function notice()
    {
        return view('public.auth.verify-email');
    }

    /**
     * Handle email verification.
     */
    public function verify(EmailVerificationRequest $request)
    {
        Log::info('✅ Email verification route hit.', [
            'user_id' => Auth::id(),
            'request_id' => $request->route('id'),
            'request_hash' => $request->route('hash'),
        ]);

        if (!Auth::check()) {
            Log::error('❌ User is not authenticated during email verification.');
            return redirect()->route('login')->with('error', __('You must be logged in to verify your email.'));
        }

        try {
            $user = Auth::user();

            if ($user->hasVerifiedEmail()) {
                Log::warning('⚠️ Email is already verified for user ID: ' . $user->id);
                return redirect()->route('user.profile')->with('warning', __('Your email is already verified.'));
            }

            $request->fulfill();

            event(new Verified($user)); // Fire verification event

            Log::info('✅ Email verification successful for user ID: ' . $user->id);
            return redirect()->route('user.profile')->with('success', __('Email verified successfully.'));
        } catch (Exception $e) {
            Log::error('❌ Email verification failed: ' . $e->getMessage());

            return redirect()->route('login')->with('error', __('An error occurred during email verification.'));
        }
    }

    /**
     * Resend the email verification notification.
     */
    public function send(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->hasVerifiedEmail()) {
                Log::info("⚠️ Email already verified for user ID: {$user->id}");
                return back()->with('warning', __('Your email is already verified.'));
            }

            $user->sendEmailVerificationNotification();

            Log::info("✅ Verification email sent to user ID: {$user->id}");
            return back()->with('success', __('Verification email sent successfully.'));
        } catch (Exception $e) {
            Log::error('❌ Failed to send email verification: ' . $e->getMessage());
            return back()->with('error', __('Failed to send verification email. Please try again later.'));
        }
    }
}
