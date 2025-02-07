<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    public function profile()
    {
        try {
            $user = Auth::user();

            return view('public.user.profile', compact('user'));
        } catch (\Exception $e) {
            throwError('Failed to load profile page', 500, ['details' => $e->getMessage()]);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            $profile = $user->profile;

            if (!$profile) {
                return redirect()->route('user.profile')->with('error', 'Profile not found.');
            }

            $validated = $request->validate([
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($user->id), // Empêche l'email d'être modifié en un email déjà existant
                ],
            ]);

            $profile->update($validated);

            return redirect()->route('user.profile')->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            throwError('Failed to update profile', 500, ['details' => $e->getMessage()]);
        }
    }

    public function editField($field)
    {
        try {
            $user = Auth::user();
            $profile = $user->profile;

            return view('components.edit-field', [
                'field' => $field,
                'value' => $profile?->$field ?? ''
            ]);
        } catch (\Exception $e) {
            throwError('Failed to load field editor', 500, ['details' => $e->getMessage()]);
        }
    }

    public function updateField(Request $request, $field)
    {
        try {
            $user = Auth::user();
            $profile = $user->profile;

            if (!$profile) {
                return response()->json(['error' => 'Profile not found'], 404);
            }

            if (!in_array($field, ['first_name', 'last_name', 'email'])) {
                return response()->json(['error' => 'Invalid field'], 400);
            }

            $validated = $request->validate([
                $field => $field === 'email'
                    ? ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)]
                    : ['nullable', 'string', 'max:255']
            ]);

            $profile->$field = $validated[$field] ?? '';
            $profile->save();

            return response()->json([
                'message' => 'Field updated successfully',
                'field' => $field,
                'value' => $profile->$field
            ]);
        } catch (\Exception $e) {
            throwError('Failed to update field', 500, ['details' => $e->getMessage()]);
        }
    }
}
