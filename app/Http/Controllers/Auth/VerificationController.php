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
    public function notice()
    {
        return view('public.auth.verify-email');
    }

    /**
     * Verify the user's email address.
     *
     * This method handles the email verification process. It logs the request,
     * checks if the user is authenticated, and verifies the email if not already verified.
     * If the email is successfully verified, it triggers the Verified event.
     * In case of any errors, it logs the error and redirects the user to the login page.
     *
     * @param \Illuminate\Foundation\Auth\EmailVerificationRequest $request
     * @return \Illuminate\Http\RedirectResponse
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

            event(new Verified($user));

            Log::info('✅ Email verification successful for user ID: ' . $user->id);
            return redirect()->route('user.profile')->with('success', __('Email verified successfully.'));
        } catch (Exception $e) {
            Log::error('❌ Email verification failed: ' . $e->getMessage());

            return redirect()->route('login')->with('error', __('An error occurred during email verification.'));
        }
    }

    /**
     * Send a verification email to the authenticated user.
     *
     * This method checks if the user's email is already verified. If it is, it logs a warning
     * and returns a response indicating that the email is already verified. If the email is not
     * verified, it sends a verification email, logs the action, and returns a success response.
     * In case of any exception, it logs the error and returns an error response.
     *
     * @param \Illuminate\Http\Request $request The current request instance.
     * @return \Illuminate\Http\RedirectResponse A redirect response with a status message.
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
