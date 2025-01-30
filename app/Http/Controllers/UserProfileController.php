<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function profile(){
        $user = Auth::user();
        return view('public.user.profile', compact('user'));
    }

    public function updateProfile(Request $request){
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            return redirect()->route('user.profile')->with('error', 'Profile not found.');
        }

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        $profile->update($validated);
        return redirect()->route('user.profile')->with('success', 'Profile updated successfully');
    }

    public function editField($field){
        $user = Auth::user();
        $profile = $user->profile;

        return view('components.edit-field', [
            'field' => $field,
            'value' => $profile ? $profile->$field : ''
        ]);
    }

    public function updateField(Request $request, $field){
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json(['error' => 'Profile not found'], 404);
        }

        $validated = $request->validate([
            $field => 'nullable|string|max:255'
        ]);
        $profile->$field = $validated[$field] ?? '';
        $profile->save();

        return view('components.view-field', [
            'field' => $field,
            'value' => $profile->$field
        ]);
    }
}
