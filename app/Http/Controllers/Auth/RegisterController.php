<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('public.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => [
                'required', 'string', 'min:8', 'confirmed',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/' // Doit contenir au moins une lettre et un chiffre
            ],
        ], [
            'password.regex' => __('The password must contain at least one letter and one number.'),
        ]);

        try {
            $user = User::create([
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);

            // Envoi automatique du mail de vérification
            $user->sendEmailVerificationNotification();

            Auth::login($user);

            return redirect()->route('user.profile')->with('success', __('Registration successful. Please verify your email address.'));
        } catch (\Exception $e) {
            throwError(__('Registration failed. Please try again.'), 500, ['details' => $e->getMessage()]);
        }
    }
}
