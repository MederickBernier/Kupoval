<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class UserProfileController extends Controller
{
    public function profile()
    {
        try {
            $user = Auth::user();

            // ✅ Ensure the user has a profile (create if missing)
            if (!$user->profile) {
                $user->profile()->create([
                    'first_name' => '',
                    'last_name' => '',
                    'title' => '',
                ]);
                Log::info("✅ Auto-created missing profile for user ID: {$user->id}");
            }

            // ✅ Ensure addresses exist
            $addresses = $user->profile->addresses ?? collect();

            // ✅ Load wishlist with related artworks to prevent null issues
            $wishlist = $user->wishlist()->with('artwork')->get();

            return view('public.user.profile', [
                'user' => $user,
                'profile' => $user->profile,
                'addresses' => $addresses,
                'wishlist' => $wishlist
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Failed to load profile page', ['error' => $e->getMessage()]);
            return abort(500, 'An error occurred while loading your profile.');
        }
    }

    public function Address(Request $request, $addressId)
    {
        try {
            $user = Auth::user();
            $address = Address::where('id', $addressId)->whereHas('userProfile', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->first();

            if (!$address) {
                return response()->json(['error' => 'Address not found'], 404);
            }

            $validated = $request->validate([
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'country' => 'required|string|max:100',
                'zipcode' => 'required|string|max:20',
            ]);

            $address->update($validated);

            return response()->json(['message' => 'Address updated successfully']);
        } catch (\Exception $e) {
            Log::error('❌ Failed to update address', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update address'], 500);
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
                    Rule::unique('users')->ignore($user->id),
                ],
            ]);

            $profile->update($validated);

            return redirect()->route('user.profile')->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            Log::error('❌ Failed to update profile', ['error' => $e->getMessage()]);
            return redirect()->route('user.profile')->with('error', 'Failed to update profile.');
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
            Log::error('❌ Failed to load field editor', ['error' => $e->getMessage()]);
            return abort(500, 'An error occurred while loading the field editor.');
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
            Log::error('❌ Failed to update field', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update field'], 500);
        }
    }
}
