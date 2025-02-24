@php use Illuminate\Support\Str @endphp
<div class="p-6 bg-soft-aqua text-charcoal-gray min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-navy-blue tracking-wide font-serif">{{ __('public/shop.browse_shop') }}</h2>
        <button wire:click="toggleFilters"
                class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-xl shadow-lg transition-transform duration-200 flex items-center hover:scale-105">
            <i class="bi bi-funnel text-lg mr-2"></i>
            {{ $filtersVisible ? __('public/shop.hide_filters') : __('public/shop.show_filters') }}
        </button>
    </div>

    @if($filtersVisible)
    <div class="mb-6 p-5 bg-gray-50 shadow-lg rounded-xl border border-deep-emerald transition-all duration-300">
        <!-- 🔹 Filters Header -->
        <div class="flex items-center space-x-2 mb-4">
            <i class="bi bi-funnel-fill text-teal-600"></i>
            <h3 class="text-xl font-semibold text-navy-blue">{{ __('public/shop.filters') }}</h3>
        </div>

        <!-- 🔹 First Row: Search + Sorting (Balanced Width) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- 🔍 Search Bar (50% Width) -->
            <div class="flex flex-col">
                <h4 class="text-gray-700 font-medium mb-1 flex items-center text-sm">
                    <i class="bi bi-search text-gray-500 mr-1"></i>{{ __('public/shop.search') }}
                </h4>
                <input type="text" wire:model.live="search"
                       class="border border-deep-emerald rounded-md px-4 py-3 bg-white text-gray-800 focus:ring-teal-500 focus:ring-2 placeholder-gray-500 shadow-sm w-full"
                       placeholder="{{ __('public/shop.search') }}">
            </div>

            <!-- 🔽 Sort By Dropdown (50% Width) -->
            <div class="flex flex-col">
                <h4 class="text-gray-700 font-medium mb-1 flex items-center text-sm">
                    <i class="bi bi-sort-down text-blue-500 mr-1"></i>{{ __('public/shop.sort_by') }}
                </h4>
                <select wire:model.live="sortBy"
                        class="border border-deep-emerald rounded-md px-4 py-2 bg-white text-gray-800 focus:ring-teal-500 focus:ring-2 shadow-sm text-sm w-full">
                    <option value="newest">{{ __('public/shop.newest_first') }}</option>
                    <option value="oldest">{{ __('public/shop.oldest_first') }}</option>
                </select>
            </div>
        </div>

        <!-- 🔹 Second Row: Price Filters -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <!-- 💰 Min Price -->
            <div class="flex flex-col">
                <h4 class="text-gray-700 font-medium mb-1 flex items-center text-sm">
                    <i class="bi bi-tag text-green-500 mr-1"></i>{{ __('public/shop.min_price') }}
                </h4>
                <input type="number" wire:model.live="priceMin"
                       min="0" step="10"
                       class="border border-deep-emerald rounded-md px-4 py-2 w-full bg-white text-gray-800 shadow-sm"
                       placeholder="{{ __('public/shop.min_price') }}">
            </div>

            <!-- 💰 Max Price -->
            <div class="flex flex-col">
                <h4 class="text-gray-700 font-medium mb-1 flex items-center text-sm">
                    <i class="bi bi-tag text-red-500 mr-1"></i>{{ __('public/shop.max_price') }}
                </h4>
                <input type="number" wire:model.live="priceMax"
                       min="0" step="10"
                       class="border border-deep-emerald rounded-md px-4 py-2 w-full bg-white text-gray-800 shadow-sm"
                       placeholder="{{ __('public/shop.max_price') }}">
            </div>
        </div>

        <!-- 🔹 Third Row: Checkboxes (Only Featured & Only Wishlisted) -->
        <div class="mb-6 flex items-center space-x-6">
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" wire:model.live="onlyFeatured" class="w-5 h-5 accent-teal-600">
                <span class="text-gray-700 font-medium text-sm">{{ __('public/shop.only_featured') }}</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" wire:model.live="onlyWishlisted" class="w-5 h-5 accent-purple-600">
                <span class="text-gray-700 font-medium text-sm">{{ __('public/shop.only_wishlisted') }}</span>
            </label>
        </div>

        <!-- 🔹 Fourth Row: Categories & Artists -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- 🏷️ Categories -->
            <div class="border border-deep-emerald rounded-xl p-4 shadow-sm bg-gray-100">
                <h4 class="text-gray-700 font-medium mb-2 flex items-center text-sm">
                    <i class="bi bi-tags text-emerald-green mr-1"></i>{{ __('public/shop.categories') }}
                </h4>
                <div class="grid grid-cols-2 gap-2 max-h-32 overflow-y-auto p-2 bg-white rounded-md">
                    @foreach($categories as $category)
                        <label class="flex items-center space-x-2 px-2 py-1 cursor-pointer hover:bg-gray-200 transition">
                            <input type="checkbox" wire:model.live="selectedCategories" value="{{ (int) $category->id }}" class="w-5 h-5 accent-emerald-green">
                            <span class="text-gray-800 text-sm">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 🎨 Artists -->
            <div class="border border-deep-emerald rounded-xl p-4 shadow-sm bg-gray-100">
                <h4 class="text-gray-700 font-medium mb-2 flex items-center text-sm">
                    <i class="bi bi-brush text-teal-600 mr-1"></i>{{ __('public/shop.artists') }}
                </h4>
                <div class="grid grid-cols-2 gap-2 max-h-32 overflow-y-auto p-2 bg-white rounded-md">
                    @foreach($artists as $artist)
                        <label class="flex items-center space-x-2 px-2 py-1 cursor-pointer hover:bg-gray-200 transition">
                            <input type="checkbox" wire:model.live="selectedArtist" value="{{ (int) $artist->id }}" class="w-5 h-5 accent-teal-600">
                            <span class="text-gray-800 text-sm">{{ $artist->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($artworks as $artwork)
            <div class="relative bg-white p-6 shadow-lg rounded-xl border border-deep-emerald transition-transform hover:scale-105 hover:shadow-xl">
                <div class="relative">
                    <img src="{{ Storage::url($artwork->image) }}"
                        alt="{{ $artwork->name }}"
                        class="w-full h-64 object-cover rounded-lg shadow-md">

                    <!-- Wishlist Button -->
                    <button wire:click="toggleWishlist({{ $artwork->id }})"
                        class="absolute top-3 right-3 bg-gray-800 bg-opacity-50 text-white p-1.5 rounded-full shadow-sm hover:bg-opacity-80 transition">
                        @if(in_array($artwork->id, $wishlist))
                            <i class="bi bi-heart-fill text-red-500 text-lg"></i>
                        @else
                            <i class="bi bi-heart text-white text-lg"></i>
                        @endif
                    </button>
                </div>

                <h3 class="mt-4 text-lg font-semibold text-navy-blue">{{ $artwork->name }}</h3>
                <p class="text-gray-600 italic">{{ $artwork->artist->name }}</p>
                <p class="text-gray-700 text-sm mt-2">{!! Str::limit($artwork->description, 120) !!}</p>

                <div class="mt-4 flex justify-between">
                    <button wire:click="addToCart({{ $artwork->id }})" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow-md transition-transform hover:scale-105">
                        {{ __('public/shop.add_to_cart') }}
                    </button>
                    <a href="{{ route('artwork.show', $artwork->slug) }}"
                    class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-2 rounded-lg shadow-md transition-transform hover:scale-105">
                        {{ __('public/shop.view_details') }}
                    </a>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-600 text-lg italic">{{ __('public/shop.no_artworks_found') }}</p>
        @endforelse
    </div>

    <div class="mt-8 flex justify-center">
        {{ $artworks->links() }}
    </div>

    <livewire:simple-cart />
</div>
