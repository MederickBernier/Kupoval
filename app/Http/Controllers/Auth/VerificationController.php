<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            $request->fulfill();
            Log::info('✅ Email verification successful.');
            return redirect()->route('user.profile')->with('success', __('Email verified successfully.'));
        } catch (\Exception $e) {
            Log::error('❌ Email verification failed: ' . $e->getMessage());
            return redirect()->route('login')->with('error', __('An error occurred during email verification.'));
        }
    }

    /**
     * Resend the email verification notification.
     */
    public function send(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return back()->with('info', __('Your email is already verified.'));
        }

        try {
            $request->user()->sendEmailVerificationNotification();
            return back()->with('status', __('Verification email sent successfully.'));
        } catch (\Exception $e) {
            Log::error('Failed to send email verification: ' . $e->getMessage());
            return back()->with('error', __('Failed to send verification email. Please try again later.'));
        }
    }
}
