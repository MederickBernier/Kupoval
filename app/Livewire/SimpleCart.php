<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class SimpleCart extends Component
{
    public $cartItems = [];
    public $cartItemCount = 0;
    public $totalPrice = 0;
    public $showCart = false;

    protected $listeners = [
        'updateQuantity' => 'updateQuantity',
        'removeFromCart' => 'removeFromCart',
        'cartUpdated' => 'cartUpdated',
    ];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

            // Load items
            $this->cartItems = $cart->items()->with('artwork')->get()->mapWithKeys(function ($item) {
                return [
                    $item->artwork_id => [
                        'id' => $item->artwork_id,
                        'name' => $item->artwork->name, // Fix: Include artwork name
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                    ]
                ];
            })->toArray();

            // ✅ Recalculate and store total price
            $this->totalPrice = CartItem::where('cart_id', $cart->id)
                ->sum(DB::raw('quantity * price'));
        } else {
            // Guest cart in session
            $this->cartItems = session()->get('cart', []);
            $this->totalPrice = session()->get('cart_total', 0);
        }

        // ✅ Update count
        $this->cartItemCount = array_sum(array_column($this->cartItems, 'quantity'));

        // ✅ Store total in session for guests
        session()->put('cart_total', $this->totalPrice);
    }

    public function toggleCart()
    {
        $this->showCart = !$this->showCart;
    }

    public function incrementQuantity($id)
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $cartItem = CartItem::where('cart_id', $cart->id)->where('artwork_id', $id)->first();

            if ($cartItem) {
                $cartItem->increment('quantity');
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                $cart[$id]['quantity']++;
                session()->put('cart', $cart);
            }
        }

        // ✅ Recalculate total
        $this->recalculateTotal();
    }

    public function decrementQuantity($id)
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $cartItem = CartItem::where('cart_id', $cart->id)->where('artwork_id', $id)->first();

            if ($cartItem && $cartItem->quantity > 1) {
                $cartItem->decrement('quantity');
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id]) && $cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
                session()->put('cart', $cart);
            }
        }

        // ✅ Recalculate total
        $this->recalculateTotal();
    }

    public function removeFromCart($id)
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();

            if ($cart) {
                CartItem::where('cart_id', $cart->id)->where('artwork_id', $id)->delete();
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
        }

        // ✅ Recalculate total
        $this->recalculateTotal();
    }

    public function recalculateTotal()
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
            $this->totalPrice = CartItem::where('cart_id', $cart->id)
                ->sum(DB::raw('quantity * price'));
        } else {
            $cart = session()->get('cart', []);
            $this->totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        }

        // ✅ Store in session for guests
        session()->put('cart_total', $this->totalPrice);

        // ✅ Refresh UI
        $this->cartItems = [];
        $this->loadCart();
        $this->dispatch('$refresh');
    }

    public function cartUpdated()
    {
        $this->cartItems = [];
        $this->loadCart();
        $this->dispatch('$refresh');
    }

    public function goToConfirmation()
    {
        return redirect()->route('checkout.confirmation');
    }

    public function render()
    {
        return view('livewire.simple-cart');
    }
}
