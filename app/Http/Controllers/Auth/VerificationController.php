<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Controller;
class VerificationController extends Controller
{
    public function notice(){
        return view('auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request){
        try{
            $request->fulfill();
            return redirect()->route('dashboard')->with('success',__('Email verified successfully'));
        }catch(\Exception $e){
            throwError(__('An error occured during email verification'),500,['exception' => $e->getMessage()]);
        }
    }

    public function send(Request $request){
        try{
            if($request->user()->hasVerifiedEmail()){
                throwError(__('Email already verified'),400);
            }
            $request->user()->sendEmailVerificationNotification();
            return back()->with('status',__('Email verification link sent'));
        }catch(\Exception $e){
            throwError(__('An error occured while resending email verification'),500,['exception' => $e->getMessage()]);
        }
    }
}
