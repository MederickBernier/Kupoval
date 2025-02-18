<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index()
    {
        return view('public.shop.index');
    }

    public function cart()
    {
        if (!Auth::check()) {
            $cart = session()->get('cart', []);
        } else {
            $cart = Auth::user()->cart()->with('items.artwork')->first();
        }

        return view('public.shop.cart', [
            'cart' => $cart ?? []
        ]);
    }
}
