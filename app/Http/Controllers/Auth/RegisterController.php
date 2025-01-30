<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

            // Envoie automatique du mail de vérification
            $user->sendEmailVerificationNotification();

            Auth::login($user);

            return redirect()->route('user.profile')->with('success', __('Registration successful. Please verify your email address.'));
        }catch(\Exception $e){
            return redirect()->route('register')->with('error', __('Registration failed. Please try again.'));
        }
    }
}
