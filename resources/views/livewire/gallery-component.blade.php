@php use Illuminate\Support\Str @endphp
<div class="p-6 page-background text-charcoal-gray min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-navy-blue tracking-wide">{{ __('public/gallery.browse_artworks') }}</h2>
        <button wire:click="toggleFilters"
            class="bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white px-6 py-3 rounded-full font-medium transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
            <i class="bi bi-funnel"></i>
            <span>{{ $filtersVisible ? __('public/gallery.hide_filters') : __('public/gallery.show_filters') }}</span>
        </button>
    </div>

    @if ($filtersVisible)
        <div class="mb-4 p-4 bg-white shadow-md rounded-lg border border-deep-emerald transition-all duration-300">
            <!-- 🔹 Filters Header -->
            <div class="flex items-center space-x-2 mb-4">
                <i class="bi bi-funnel-fill text-teal-600"></i>
                <h3 class="text-xl font-semibold text-navy-blue">{{ __('public/gallery.filters') }}</h3>
            </div>

            <!-- 🔹 First Row: Search + Sorting + Event Filters (Inline) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- 🔍 Search Bar -->
                <div class="flex flex-col">
                    <h4 class="text-gray-700 font-medium mb-1 flex items-center text-sm">
                        <i class="bi bi-search text-gray-500 mr-1"></i>{{ __('public/gallery.search') }}
                    </h4>
                    <input type="text" wire:model.live="search"
                        class="border border-deep-emerald rounded-md px-4 py-2 bg-white text-gray-800 focus:ring-emerald-green focus:ring-1 placeholder-gray-500 shadow-sm w-full"
                        placeholder="{{ __('public/gallery.search') }}">
                </div>

                <!-- 🔽 Sort By Dropdown -->
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

                <!-- 📅 Event Filter -->
                <div class="flex flex-col">
                    <h4 class="text-gray-700 font-medium mb-1 flex items-center text-sm">
                        <i class="bi bi-calendar-event text-orange-500 mr-1"></i>{{ __('public/gallery.event') }}
                    </h4>
                    <select wire:model.live="selectedEvent"
                        class="border border-deep-emerald rounded-md px-4 py-2 bg-white text-gray-800 focus:ring-emerald-green focus:ring-1 shadow-sm text-sm w-full">
                        <option value="">{{ __('public/gallery.all_events') }}</option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}">{{ $event->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- 🔹 Second Row: Categories & Artists (Two Columns) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <!-- 🏷️ Categories Filter -->
                <div class="border border-deep-emerald rounded-md p-3 shadow-sm bg-gray-100">
                    <h4 class="text-gray-700 font-medium mb-2 flex items-center text-sm">
                        <i class="bi bi-tags text-emerald-green mr-1"></i>{{ __('public/gallery.categories') }}
                    </h4>
                    <div
                        class="max-h-24 overflow-y-auto scrollbar-thin scrollbar-thumb-emerald-400 scrollbar-track-gray-200 p-1 bg-white rounded-md">
                        @foreach ($categories as $category)
                            <label
                                class="flex items-center space-x-2 px-2 py-1 rounded-md cursor-pointer hover:bg-gray-300 transition">
                                <input type="checkbox" wire:model.live="selectedCategories"
                                    value="{{ (int) $category->id }}" class="w-4 h-4 accent-emerald-green">
                                <span class="text-gray-800 text-sm">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 🎨 Artists Filter -->
                <div class="border border-deep-emerald rounded-md p-3 shadow-sm bg-gray-100">
                    <h4 class="text-gray-700 font-medium mb-2 flex items-center text-sm">
                        <i class="bi bi-brush text-teal-600 mr-1"></i>{{ __('public/gallery.artists') }}
                    </h4>
                    <div
                        class="max-h-24 overflow-y-auto scrollbar-thin scrollbar-thumb-teal-400 scrollbar-track-gray-200 p-1 bg-white rounded-md">
                        @foreach ($artists as $artist)
                            <label
                                class="flex items-center space-x-2 px-2 py-1 rounded-md cursor-pointer hover:bg-gray-300 transition">
                                <input type="checkbox" wire:model.live="selectedArtists"
                                    value="{{ (int) $artist->id }}" class="w-4 h-4 accent-teal-600">
                                <span class="text-gray-800 text-sm">{{ $artist->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 🔹 Reset Filters Button -->
            <div class="mt-4 flex justify-end">
                <button wire:click="resetFilters"
                    class="bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    <span>{{ __('public/gallery.reset_filters') }}</span>
                </button>
            </div>
        </div>
    @endif

    <!-- ✅ Fully Responsive Grid (4 items on large, 3 on medium, 2 on small screens) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($artworks as $artwork)
            <div
                class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="relative">
                    <img src="{{ Storage::url($artwork->image) }}" alt="{{ $artwork->name }}"
                        class="w-full h-60 object-cover transition-transform duration-300 group-hover:scale-105"
                        loading="lazy">
                </div>

                <div class="p-5">
                    <h3 class="font-title text-lg font-semibold text-heading mb-1">{{ $artwork->name }}</h3>
                    <p class="font-accent text-sm text-gray-600 italic mb-2">{{ $artwork->artist->name }}</p>
                    <p class="text-sm text-gray-700 mb-4 line-clamp-3">{!! Str::limit($artwork->description, 120) !!}</p>

                    <div class="flex justify-center">
                        <a href="{{ route('artwork.show', $artwork->slug) }}"
                            class="bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="bi bi-eye text-sm"></i>
                            <span>{{ __('public/gallery.view_details') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-600 text-lg italic">
                {{ __('public/gallery.no_artworks_found') }}</p>
        @endforelse
    </div>

    <div class="mt-8 flex justify-center">
        {{ $artworks->links() }}
    </div>
</div>
