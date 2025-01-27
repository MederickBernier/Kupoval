<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm(){
        return view('public.auth.register');
    }

    public function register(Request $request){
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try{
            $user = User::create([
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);

            Auth::login($user);

            $user->sendEmailVerificationNotification();

            return redirect()->route('user_profile')->with('success',__('Registration successful.  Please check your email to verify your account.'));
        }catch(\Exception $e){
            throwError(__('Registration failed.  Please try again.'),500,['exception' => $e->getMessage()]);
        }
    }
}
