<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Controller;

class VerificationController extends Controller
{
    public function notice()
    {
        return view('public.auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request)
    {
        try {
            $request->fulfill();
            return redirect()->route('user.profile')->with('success', __('Email verified successfully'));
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', __('An error occurred during email verification'));
        }
    }

    public function send(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return back()->with('info', __('Email already verified.'));
        }

        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', __('Verification email sent.'));
    }
}
