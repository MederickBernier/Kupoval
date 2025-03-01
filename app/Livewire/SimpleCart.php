<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

/**
 * Class SimpleCart
 *
 * A Livewire component that manages a simple shopping cart.
 *
 * Properties:
 * @property array $cartItems - The items in the cart.
 * @property int $cartItemCount - The total number of items in the cart.
 * @property float $totalPrice - The total price of the items in the cart.
 * @property bool $showCart - Flag to show or hide the cart.
 *
 * Listeners:
 * @property array $listeners - The event listeners for the component.
 *
 * Methods:
 * @method void mount() - Initializes the component and loads the cart.
 * @method void loadCart() - Loads the cart items and calculates the total price.
 * @method void toggleCart() - Toggles the visibility of the cart.
 * @method void incrementQuantity(int $id) - Increments the quantity of a cart item.
 * @method void decrementQuantity(int $id) - Decrements the quantity of a cart item.
 * @method void removeFromCart(int $id) - Removes an item from the cart.
 * @method void recalculateTotal() - Recalculates the total price of the cart.
 * @method void cartUpdated() - Updates the cart items and refreshes the component.
 * @method \Illuminate\Http\RedirectResponse goToConfirmation() - Redirects to the checkout confirmation page.
 * @method \Illuminate\View\View render() - Renders the Livewire component view.
 */
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

            $this->cartItems = $cart->items()->with('artwork')->get()->mapWithKeys(function ($item) {
                return [
                    $item->artwork_id => [
                        'id' => $item->artwork_id,
                        'name' => $item->artwork->name,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                    ]
                ];
            })->toArray();

            $this->totalPrice = CartItem::where('cart_id', $cart->id)
                ->sum(DB::raw('quantity * price'));
        } else {
            $this->cartItems = session()->get('cart', []);
            $this->totalPrice = session()->get('cart_total', 0);
        }

        $this->cartItemCount = array_sum(array_column($this->cartItems, 'quantity'));

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

        $this->recalculateTotal();

        $this->cartItems = [];
        $this->cartItemCount = 0;
        $this->dispatch('cartUpdated');
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

        session()->put('cart_total', $this->totalPrice);

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
