<div class="p-6 bg-soft-aqua text-charcoal-gray min-h-screen">
    <!-- 🎨 Filter Panel Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-navy-blue tracking-wide">{{ __('public/gallery.browse_artworks') }}</h2>
        <button wire:click="toggleFilters"
                class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg shadow-md transition-transform duration-200 flex items-center hover:scale-105">
            <i class="bi bi-funnel text-lg mr-2"></i>
            {{ $filtersVisible ? __('public/gallery.hide_filters') : __('public/gallery.show_filters') }}
        </button>
    </div>

    @if($filtersVisible)
    <div class="mb-4 p-4 bg-white shadow-md rounded-lg border border-deep-emerald transition-all duration-300">
        <!-- 🔹 Filters Header -->
        <div class="flex items-center space-x-2">
            <i class="bi bi-funnel-fill text-teal-600"></i>
            <h3 class="text-xl font-semibold text-navy-blue">{{ __('public/gallery.filters') }}</h3>
        </div>

        <!-- 🔹 First Row: Search, Sort, Event (NOW TAKES FULL WIDTH) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4">
            <!-- 🔍 Search Input -->
            <div class="flex flex-col md:col-span-3">
                <h4 class="text-gray-700 font-medium mb-1 flex items-center text-sm">
                    <i class="bi bi-search text-gray-500 mr-1"></i>{{ __('public/gallery.search') }}
                </h4>
                <input type="text" wire:model.live="search"
                       class="border border-deep-emerald rounded-md px-4 py-2 w-full bg-white text-gray-800 focus:ring-emerald-green focus:ring-1 placeholder-gray-500 shadow-sm"
                       placeholder="{{ __('public/gallery.search') }}">
            </div>

            <!-- 📊 Sorting & Event (Properly Spaced) -->
            <div class="grid grid-cols-2 gap-3 md:col-span-3">
                <!-- Sort Dropdown -->
                <div class="flex flex-col">
                    <h4 class="text-gray-700 font-medium mb-1 flex items-center text-sm">
                        <i class="bi bi-sort-down text-blue-500 mr-1"></i>{{ __('public/gallery.sort_by') }}
                    </h4>
                    <select wire:model.live="sortBy"
                            class="border border-deep-emerald rounded-md px-4 py-2 bg-white text-gray-800 focus:ring-emerald-green focus:ring-1 shadow-sm text-sm w-full">
                        <option value="newest">{{ __('public/gallery.newest_first') }}</option>
                        <option value="oldest">{{ __('public/gallery.oldest_first') }}</option>
                        <option value="featured">{{ __('public/gallery.featured_first') }}</option>
                    </select>
                </div>

                <!-- Event Dropdown -->
                <div class="flex flex-col">
                    <h4 class="text-gray-700 font-medium mb-1 flex items-center text-sm">
                        <i class="bi bi-calendar-event text-orange-500 mr-1"></i>{{ __('public/gallery.event') }}
                    </h4>
                    <select wire:model.live="selectedEvent"
                            class="border border-deep-emerald rounded-md px-4 py-2 bg-white text-gray-800 focus:ring-emerald-green focus:ring-1 shadow-sm text-sm w-full">
                        <option value="">{{ __('public/gallery.all_events') }}</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- 🔹 Second Row: Categories & Artists -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
            <!-- 📂 Category Filter -->
            <div class="border border-deep-emerald rounded-md p-3 shadow-sm bg-gray-100">
                <h4 class="text-gray-700 font-medium mb-2 flex items-center text-sm">
                    <i class="bi bi-tags text-emerald-green mr-1"></i>{{ __('public/gallery.categories') }}
                </h4>
                <div class="max-h-24 overflow-y-auto scrollbar-thin scrollbar-thumb-emerald-400 scrollbar-track-gray-200 p-1 bg-white rounded-md">
                    @foreach($categories as $category)
                        <label class="flex items-center space-x-2 px-2 py-1 rounded-md cursor-pointer hover:bg-gray-300 transition">
                            <input type="checkbox" wire:model.live="selectedCategories" value="{{ (int) $category->id }}" class="w-4 h-4 accent-emerald-green">
                            <span class="text-gray-800 text-sm">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 🎨 Artist Filter -->
            <div class="border border-deep-emerald rounded-md p-3 shadow-sm bg-gray-100">
                <h4 class="text-gray-700 font-medium mb-2 flex items-center text-sm">
                    <i class="bi bi-brush text-teal-600 mr-1"></i>{{ __('public/gallery.artists') }}
                </h4>
                <div class="max-h-24 overflow-y-auto scrollbar-thin scrollbar-thumb-teal-400 scrollbar-track-gray-200 p-1 bg-white rounded-md">
                    @foreach($artists as $artist)
                        <label class="flex items-center space-x-2 px-2 py-1 rounded-md cursor-pointer hover:bg-gray-300 transition">
                            <input type="checkbox" wire:model.live="selectedArtists" value="{{ (int) $artist->id }}" class="w-4 h-4 accent-teal-600">
                            <span class="text-gray-800 text-sm">{{ $artist->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 🔥 Reset Filters Button -->
        <div class="mt-4 flex justify-end">
            <button wire:click="resetFilters" onclick="window.location.reload();"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-4 py-2 rounded-md shadow-md border border-orange-700 transition-all duration-200 flex items-center text-sm">
                <i class="bi bi-arrow-counterclockwise mr-2"></i> {{ __('public/gallery.reset_filters') }}
            </button>
        </div>
    </div>
@endif

    <!-- 🎭 Artwork Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($artworks as $artwork)
            <div class="bg-white p-6 shadow-lg rounded-xl border border-deep-emerald transition-transform hover:scale-105 hover:shadow-xl">
                <img src="{{ Storage::url($artwork->image) }}"
                    alt="{{ $artwork->name }}"
                    class="w-full h-60 object-cover rounded-lg shadow-md"
                    loading="lazy">
                <h3 class="mt-4 text-lg font-semibold text-navy-blue">{{ $artwork->name }}</h3>
                <p class="text-gray-600 italic">{{ $artwork->artist->name }}</p>

                <!-- View Details Button -->
                <div class="mt-4 flex justify-between">
                    <a href="{{ route('artwork.show', $artwork->id) }}"
                    class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg shadow-md transition">
                        {{ __('public/gallery.view_details') }}
                    </a>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-600 text-lg italic">{{ __('public/gallery.no_artworks_found') }}</p>
        @endforelse
    </div>

    <!-- 📄 Pagination -->
    <div class="mt-8 flex justify-center">
        {{ $artworks->links() }}
    </div>
</div>
