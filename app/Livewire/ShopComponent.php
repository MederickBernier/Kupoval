<?php

namespace App\Livewire;

use App\Models\Artwork;
use App\Models\Category;
use App\Models\Event;
use App\Models\Artist;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Session;

class ShopComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategories = [];
    public $selectedEvent = null;
    public $selectedArtist = null;
    public $priceMax = 10000;
    public $onlyDiscounted = false;
    public $onlyFeatured = false;
    public $sortBy = 'newest';
    public $filtersVisible = true;
    public $wishlist = [];
    public $cartItems = [];
    public $onlyWishlisted = false;

    protected $listeners = ['refreshShop' => '$refresh'];

    public function mount()
    {
        $this->filtersVisible = false;
        if (Auth::check()) {
            // Load user's wishlist from database
            $this->wishlist = Auth::user()->wishlist()->pluck('artwork_id')->toArray();
        } else {
            // Load wishlist from session for guests
            $this->wishlist = Session::get('wishlist', []);
        }
    }

    public function updating()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'selectedCategories',
            'selectedEvent',
            'selectedArtist',
            'priceMax',
            'onlyDiscounted',
            'onlyFeatured',
            'sortBy'
        ]);
        $this->dispatch('$refresh');
    }

    public function toggleFilters()
    {
        $this->filtersVisible = !$this->filtersVisible;
    }

    public function addToCart($id)
    {
        $artwork = Artwork::find($id);

        if (!$artwork) {
            return;
        }

        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

            // ✅ Get the existing cart item, if it exists
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('artwork_id', $id)
                ->first();

            if ($cartItem) {
                // ✅ If item already exists, increment quantity
                $cartItem->increment('quantity');
            } else {
                // ✅ If new, create it with quantity = 1
                CartItem::create([
                    'cart_id' => $cart->id,
                    'artwork_id' => $id,
                    'quantity' => 1,
                    'price' => $artwork->initial_price,
                ]);
            }

            // ✅ Recalculate and update total in session
            $totalPrice = CartItem::where('cart_id', $cart->id)
                ->sum(DB::raw('quantity * price'));
        } else {
            // ✅ Guest cart in session
            $cart = session()->get('cart', []);
            if (isset($cart[$id])) {
                $cart[$id]['quantity']++;
            } else {
                $cart[$id] = [
                    'id' => $id,
                    'name' => $artwork->name,
                    'price' => $artwork->initial_price,
                    'quantity' => 1,
                ];
            }
            session()->put('cart', $cart);

            // ✅ Recalculate total for session cart
            $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        }

        // ✅ Store updated total in session
        session()->put('cart_total', $totalPrice);

        // ✅ Ensure UI refresh
        $this->dispatch('cartUpdated')->to(SimpleCart::class);
    }

    public function toggleWishlist($artworkId)
    {
        if (Auth::check()) {
            // User is logged in, handle wishlist in database
            $user = Auth::user();
            $existingWishlistItem = $user->wishlist()->where('artwork_id', $artworkId)->first();

            if ($existingWishlistItem) {
                // Remove from database wishlist
                $existingWishlistItem->delete();
                $this->wishlist = array_values(array_filter($this->wishlist, fn($id) => $id !== $artworkId));
            } else {
                // Add to database wishlist
                $user->wishlist()->create(['artwork_id' => $artworkId]);
                $this->wishlist[] = $artworkId;
            }
        } else {
            // Guest user - manage wishlist in session
            if (in_array($artworkId, $this->wishlist)) {
                $this->wishlist = array_values(array_filter($this->wishlist, fn($id) => $id !== $artworkId));
            } else {
                $this->wishlist[] = $artworkId;
            }
            Session::put('wishlist', $this->wishlist);
        }

        $this->dispatch('$refresh');
    }

    public function render()
    {
        $query = Artwork::where('is_on_sale', true);

        if (!empty($this->search)) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($this->search) . '%']);
        }

        if (!empty($this->selectedCategories)) {
            $query->whereHas('categories', function ($q) {
                $q->whereIn('categories.id', $this->selectedCategories);
            });
        }

        if (!empty($this->selectedEvent)) {
            $query->where('event_id', $this->selectedEvent);
        }

        if (!empty($this->selectedArtist)) {
            $query->where('artist_id', $this->selectedArtist);
        }

        if (!empty($this->priceMax)) {
            $query->where('initial_price', '<=', $this->priceMax);
        }

        if ($this->onlyFeatured) {
            $query->where('is_featured', true);
        }

        if ($this->onlyWishlisted && Auth::check()) {
            $query->whereIn('id', $this->wishlist);
        }

        switch ($this->sortBy) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return view('livewire.shop-component', [
            'artworks' => $query->paginate(12),
            'categories' => Category::all(),
            'events' => Event::all(),
            'artists' => Artist::all(),
            'wishlist' => $this->wishlist,
        ]);
    }
}
