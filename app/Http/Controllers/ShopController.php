<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ShopController extends Controller
{
    /**
     * Display the shop index page.
     *
     * This method attempts to load the shop index view. If an exception occurs,
     * it logs the error and redirects the user to the home page with an error message.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return view('public.shop.index');
        } catch (\Exception $e) {
            Log::error('❌ Failed to load shop index page', ['error' => $e->getMessage()]);
            return redirect()->route('home')->with('error', __('An error occurred while loading the shop.'));
        }
    }

    /**
     * Display the user's shopping cart.
     *
     * This method handles the display of the shopping cart for both authenticated users and guests.
     * For guests, the cart is loaded from the session. For authenticated users, the cart is loaded
     * from the database along with the associated items and artwork.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     *     Returns the cart view if successful, or redirects to the shop index with an error message if an exception occurs.
     */
    public function cart()
    {
        try {
            if (!Auth::check()) {
                $cart = session()->get('cart', []);
                Log::info('🛒 Guest cart loaded from session.');
            } else {
                $cart = Auth::user()->cart()->with('items.artwork')->first();
                Log::info('🛒 Authenticated user cart loaded.', ['user_id' => Auth::id()]);
            }

            if (empty($cart) || (is_array($cart) && count($cart) === 0)) {
                Log::warning("⚠️ Cart is empty.", ['user_id' => Auth::id() ?? 'guest']);
                return view('public.shop.cart', ['cart' => $cart])->with('warning', __('Your cart is empty.'));
            }

            return view('public.shop.cart', ['cart' => $cart]);
        } catch (\Exception $e) {
            Log::error('❌ Failed to load cart page', ['error' => $e->getMessage()]);
            return redirect()->route('shop.index')->with('error', __('An error occurred while loading your cart.'));
        }
    }
}
