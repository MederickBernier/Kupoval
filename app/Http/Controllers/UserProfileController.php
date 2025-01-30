<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function profile(){
        return view('public.user.profile');
    }

    public function updateProfile(Request $request){
        $user = Auth::user()->profile();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        $user->update($validated);
        return redirect()->route('user.profile')->with('success', 'Profile updated successfully');
    }

    public function editField($field){
        $user = Auth::user()->profile;
        return view('components.edit-field', [
            'field' => $field,
            'value' => $user->$field
        ]);
    }

    public function updateField(Request $request, $field){
        $user = Auth::user()->profile;

        $validated = $request->validate([
            $field => 'required|string|max:255'
        ]);
        $user->$field = $validated[$field];
        $user->save();
        return view('components.view-field',[
        'field' => $field,
        'value' => $user->$field
        ]);
    }
}
