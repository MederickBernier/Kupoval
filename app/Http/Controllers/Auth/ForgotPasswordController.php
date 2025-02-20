<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Mail\PasswordReset;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            $user = \App\Models\User::where('email', $request->email)->first();
            $token = DB::table('password_resets')->where('email', $user->email)->first()->token ?? null;

            if ($token) {
                $resetUrl = url(route('password.reset', ['token' => $token, 'email' => $user->email]));

                // Send the password reset email
                Mail::to($user->email)->send(new PasswordReset($user, $resetUrl));
            }

            return back()->with('status', __('We have emailed your password reset link.'));
        }

        return back()->withErrors(['email' => __('Unable to send password reset email.')]);
    }
}
