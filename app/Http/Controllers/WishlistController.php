<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function remove($id)
    {
        $wishlistItem = Auth::user()->wishlist()->find($id);

        if ($wishlistItem) {
            $wishlistItem->delete();
            return back()->with('success', __('public/profile.wishlist_removed'));
        }

        return back()->with('error', __('public/profile.wishlist_not_found'));
    }
}
