<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class UserProfileController extends Controller
{
    /**
     * Display the user's profile page.
     *
     * This method retrieves the authenticated user's profile information,
     * including addresses and wishlist items, and displays it on the profile page.
     * If the user is not authenticated, they are redirected to the login page.
     * If the user does not have a profile, a new profile is automatically created.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     *   Redirects to the login page if the user is not authenticated,
     *   or to the home page if an error occurs while loading the profile.
     *   Otherwise, returns the profile view with user data.
     */
    public function profile()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', __('auth.not_authenticated'));
            }

            if (!$user->profile) {
                $user->profile()->create([
                    'first_name' => '',
                    'last_name' => '',
                    'title' => '',
                ]);
                Log::info("✅ Auto-created missing profile for user ID: {$user->id}");
            }

            $addresses = $user->profile->addresses ?? collect();
            $wishlist = $user->wishlist()->with('artwork')->get();

            return view('public.user.profile', [
                'user' => $user,
                'profile' => $user->profile,
                'addresses' => $addresses,
                'wishlist' => $wishlist
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Failed to load profile page', ['error' => $e->getMessage()]);
            return redirect()->route('home')->with('error', __('An error occurred while loading your profile.'));
        }
    }

    /**
     * Update the specified address for the authenticated user.
     *
     * @param \Illuminate\Http\Request $request The request instance containing the address data.
     * @param int $addressId The ID of the address to be updated.
     * @return \Illuminate\Http\JsonResponse A JSON response indicating the result of the update operation.
     *
     * @throws \Illuminate\Validation\ValidationException If the request validation fails.
     * @throws \Exception If an unexpected error occurs during the update process.
     */
    public function Address(Request $request, $addressId)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $address = Address::where('id', $addressId)
                ->whereHas('userProfile', fn($q) => $q->where('user_id', $user->id))
                ->first();

            if (!$address) {
                Log::warning("⚠️ Address not found for update", ['user_id' => $user->id, 'address_id' => $addressId]);
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
            Log::info("✅ Address updated successfully", ['user_id' => $user->id, 'address_id' => $addressId]);

            return response()->json(['message' => 'Address updated successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning("⚠️ Validation error updating address", ['errors' => $e->errors()]);
            return response()->json(['error' => 'Invalid address input'], 422);
        } catch (\Exception $e) {
            Log::error('❌ Failed to update address', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update address'], 500);
        }
    }

    /**
     * Update the user's profile.
     *
     * This method handles the request to update the authenticated user's profile.
     * It validates the input data, updates the user's profile, and logs the outcome.
     *
     * @param \Illuminate\Http\Request $request The incoming request containing profile data.
     * @return \Illuminate\Http\RedirectResponse A redirect response to the user profile route with a success or error message.
     *
     * @throws \Illuminate\Validation\ValidationException If the validation of the input data fails.
     * @throws \Exception If any other error occurs during the profile update process.
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user || !$user->profile) {
                return redirect()->route('user.profile')->with('error', __('Profile not found.'));
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

            $user->profile->update($validated);
            Log::info("✅ Profile updated successfully", ['user_id' => $user->id]);

            return redirect()->route('user.profile')->with('success', __('Profile updated successfully'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning("⚠️ Validation error updating profile", ['errors' => $e->errors()]);
            return redirect()->route('user.profile')->with('error', __('Invalid profile data'));
        } catch (\Exception $e) {
            Log::error('❌ Failed to update profile', ['error' => $e->getMessage()]);
            return redirect()->route('user.profile')->with('error', __('Failed to update profile.'));
        }
    }

    /**
     * Edit a specific user profile field.
     *
     * This method retrieves the authenticated user and attempts to load the view
     * for editing a specific profile field. If the user is not authenticated, it
     * redirects to the login page with an error message. If an exception occurs
     * during the process, it logs the error and returns a 500 error response.
     *
     * @param string $field The profile field to be edited.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View|\Illuminate\Http\Response
     */
    public function editField($field)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', __('auth.not_authenticated'));
            }

            return view('components.edit-field', [
                'field' => $field,
                'value' => $user->profile?->$field ?? ''
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Failed to load field editor', ['error' => $e->getMessage()]);
            return abort(500, __('An error occurred while loading the field editor.'));
        }
    }

    /**
     * Update a specific field in the user's profile.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     * @param string $field The profile field to be updated (must be 'first_name', 'last_name', or 'email').
     * @return \Illuminate\Http\JsonResponse JSON response indicating success or failure.
     *
     * @throws \Illuminate\Validation\ValidationException If the validation fails.
     * @throws \Exception If any other error occurs during the update process.
     */
    public function updateField(Request $request, $field)
    {
        try {
            $user = Auth::user();
            if (!$user || !$user->profile) {
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

            $user->profile->update([$field => $validated[$field]]);
            Log::info("✅ Field updated successfully", ['user_id' => $user->id, 'field' => $field]);

            return response()->json([
                'message' => __('Field updated successfully'),
                'field' => $field,
                'value' => $user->profile->$field
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning("⚠️ Validation error updating field", ['errors' => $e->errors()]);
            return response()->json(['error' => __('Invalid input')], 422);
        } catch (\Exception $e) {
            Log::error('❌ Failed to update field', ['error' => $e->getMessage()]);
            return response()->json(['error' => __('Failed to update field')], 500);
        }
    }
}
