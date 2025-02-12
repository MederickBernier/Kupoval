@extends('layouts.public')

@section('content')
<div class="p-6 bg-soft-aqua text-charcoal-gray min-h-screen">
    <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg p-8 flex flex-col lg:flex-row gap-8">

        <!-- Left Section: Artwork Image -->
        <div class="flex-none w-full lg:w-1/3 relative">
            <a href="{{ Storage::url($artwork->image) }}" class="artwork-lightbox" data-lightbox="gallery" data-title="{{ $artwork->name }}">
                <img src="{{ Storage::url($artwork->image) }}" alt="{{ $artwork->name }}" class="w-full h-80 object-cover rounded-lg shadow-xl cursor-pointer transition-transform duration-300">
            </a>

            <!-- Status Badges (Updated for compact design) -->
            <div class="absolute top-4 left-4 flex flex-col space-y-1">
                @if($artwork->is_featured)
                    <span class="bg-teal-600 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center">
                        <i class="bi bi-star-fill mr-1"></i> {{ __('Featured') }}
                    </span>
                @endif
                @if($artwork->is_on_sale)
                    <span class="bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center">
                        <i class="bi bi-tag-fill mr-1"></i> {{ __('For Sale') }}
                    </span>
                @endif
                @if($artwork->is_for_event && $artwork->event)
                    <span class="bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center">
                        <i class="bi bi-calendar-event-fill mr-1"></i> {{ __('Event Exclusive') }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Right Section: Content (Details, Artist Info, Event, etc.) -->
        <div class="flex-1 space-y-6">
            <!-- Artwork Title -->
            <h2 class="text-3xl font-bold text-navy-blue">{{ $artwork->name }}</h2>

            <!-- Artwork Details (Dimensions & Price) -->
            <div class="bg-teal-50 p-4 rounded-lg">
                <h3 class="text-xl font-semibold text-teal-600">{{ __('Artwork Details') }}</h3>
                <ul class="text-gray-700 list-disc list-inside mt-2">
                    <li><strong>{{ __('Dimensions') }}:</strong> {{ $artwork->width }} cm × {{ $artwork->height }} cm</li>
                </ul>
            </div>

            <!-- Description -->
            <div class="bg-teal-50 p-4 rounded-lg">
                <h3 class="text-xl font-semibold text-teal-600">{{ __('Description') }}</h3>
                <p class="text-gray-700">{!! $artwork->description !!}</p>
            </div>

            <!-- Categories -->
            <div class="bg-teal-50 p-4 rounded-lg">
                <h3 class="text-xl font-semibold text-teal-600">{{ __('Categories') }}</h3>
                @if($artwork->categories->isNotEmpty())
                    <div class="flex flex-wrap gap-3 mt-4">
                        @foreach($artwork->categories as $category)
                            <span class="bg-teal-600 text-white px-6 py-2 rounded-full">{{ $category->name }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-700 italic">{{ __('No categories available for this artwork.') }}</p>
                @endif
            </div>

            <!-- Event Info -->
            @if($artwork->event)
            <div class="bg-teal-50 p-4 rounded-lg">
                <h3 class="text-xl font-semibold text-teal-600">{{ __('Event Details') }}</h3>
                <p class="text-gray-700">
                    <strong>{{ $artwork->event->name }}</strong> <br>
                    {{ __('From') }} <span class="font-semibold text-teal-600">{{ $artwork->event->start_date->format('M d, Y') }}</span>
                    {{ __('to') }} <span class="font-semibold text-teal-600">{{ $artwork->event->end_date->format('M d, Y') }}</span>
                </p>
                @if($artwork->event->location)
                    <p class="mt-2">
                        <strong>{{ __('Location') }}:</strong> {{ $artwork->event->location }}
                    </p>
                @endif
                <p class="mt-2 text-sm text-gray-600">
                    {{ $artwork->event->description }}
                </p>
            </div>
            @endif

            <!-- Artist Info -->
            <div class="bg-teal-50 p-4 rounded-lg">
                <h3 class="text-xl font-semibold text-teal-600">{{ __('Artist') }}</h3>
                <div class="flex items-center space-x-6 mt-4">
                    <img src="{{ asset($artwork->artist->photo) }}" alt="{{ $artwork->artist->name }}" class="w-24 h-24 object-cover rounded-full border-4 border-deep-emerald shadow-md">
                    <div>
                        <p class="text-gray-800 text-lg">{{ $artwork->artist->name }}</p>
                        <p class="text-gray-600 text-sm italic mt-2">{{ $artwork->artist->bio }}</p>
                    </div>
                </div>
            </div>

            <!-- Back to Gallery Button -->
            <div class="mt-8 flex justify-center">
                <a href="{{ route('gallery') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-8 py-4 rounded-lg shadow-md transition-all duration-200 flex items-center text-lg">
                    <i class="bi bi-arrow-left mr-2"></i>{{ __('Back to Gallery') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- Include SimpleLightbox -->
    <script src="https://cdn.jsdelivr.net/npm/simplelightbox@2.4.0/dist/simple-lightbox.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var lightbox = new SimpleLightbox('.artwork-lightbox', {
                overlayOpacity: 0.9,  // Darkens the background further for better focus on the image
            });
        });
    </script>
@endpush
