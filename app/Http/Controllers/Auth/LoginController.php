<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm(){
        return view('public.auth.login');
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try{
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                $request->session()->regenerate();

                return redirect()->intended(route('user_profile'))->with('success',__('Login successful.'));
            }
            return back()->withErrors([
                'email' => __('The provided credentials do not match our records.'),
            ]);
        }catch(\Exception $e){
            throwError(__('Login failed.  Please try again later.'),500,['exception' => $e->getMessage()]);
        }
    }

    public function logout(Request $request){
        try{
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('home');

        }catch(\Exception $e){
            throwError(__('Logout failed.  Please try again later.'),500,['exception' => $e->getMessage()]);
        }
    }
}
