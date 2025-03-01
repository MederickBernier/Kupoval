<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    /**
     * Remove a wishlist item for the authenticated user.
     *
     * @param int $id The ID of the wishlist item to remove.
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the wishlist item is not found.
     * @throws \Exception If there is an error during the removal process.
     */
    public function remove($id)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return back()->with('error', __('auth.not_authenticated'));
            }

            $wishlistItem = $user->wishlist()->find($id);

            if (!$wishlistItem) {
                return back()->with('error', __('public/profile.wishlist_not_found'));
            }

            $wishlistItem->delete();
            Log::info("✅ Wishlist item removed", ['user_id' => $user->id, 'artwork_id' => $id]);

            return back()->with('success', __('public/profile.wishlist_removed'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("⚠️ Wishlist item not found", ['user_id' => Auth::id(), 'artwork_id' => $id]);
            return back()->with('error', __('public/profile.wishlist_not_found'));
        } catch (\Exception $e) {
            Log::error("❌ Failed to remove wishlist item", ['error' => $e->getMessage(), 'user_id' => Auth::id(), 'artwork_id' => $id]);
            return back()->with('error', __('public/profile.wishlist_remove_error'));
        } finally {
            Log::info("🔎 Wishlist remove attempt", ['user_id' => Auth::id(), 'artwork_id' => $id]);
        }
    }
}
