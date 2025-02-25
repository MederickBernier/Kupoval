<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Mail\PasswordResetMail;
use Throwable;
use Exception;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /**
     * Handle sending the password reset link via email.
     */
    public function sendResetLinkEmail(Request $request)
    {
        // ✅ Validate Input
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        try {
            Log::info("📩 Password reset request received for: {$request->email}");

            // ✅ Attempt to send reset link
            $status = Password::sendResetLink($request->only('email'));

            if ($status !== Password::RESET_LINK_SENT) {
                Log::warning("⚠️ Password reset email could not be sent to: {$request->email}");
                return back()->withErrors(['email' => __('Unable to send password reset email.')]);
            }

            // ✅ Retrieve User
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                Log::error("❌ User not found in database after password reset request: {$request->email}");
                return back()->withErrors(['email' => __('Unable to process request. Please try again.')]);
            }

            // ✅ Retrieve Reset Token
            $tokenData = DB::table('password_resets')->where('email', $user->email)->first();
            $token = $tokenData->token ?? null;

            if (!$token) {
                Log::error("❌ Password reset token not found for: {$user->email}");
                return back()->withErrors(['email' => __('Unable to generate reset token. Please try again.')]);
            }

            // ✅ Generate Reset URL
            $resetUrl = url(route('password.reset', ['token' => $token, 'email' => $user->email]));

            Log::info("🔗 Password reset URL generated for: {$user->email}");

            // ✅ Send Reset Email
            Mail::to($user->email)->send(new PasswordResetMail($user, $resetUrl));

            Log::info("✅ Password reset email sent successfully to: {$user->email}");

            return back()->with('success', __('We have emailed your password reset link.'));
        } catch (Throwable $e) {
            Log::error("❌ Password reset process failed: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return back()->withErrors(['email' => __('An error occurred while processing your request. Please try again later.')]);
        } finally {
            Log::info("🔍 Password reset process finalized for email: {$request->email}");
        }
    }
}
