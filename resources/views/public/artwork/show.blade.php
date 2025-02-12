@extends('layouts.public')

@section('content')
<div class="p-6 bg-soft-aqua text-charcoal-gray min-h-screen">
    <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg p-8 flex flex-col lg:flex-row gap-8">

        <!-- Left Section: Artwork Image -->
        <div class="flex-none w-full lg:w-1/3 relative">
            <!-- Artwork Image with Lightbox -->
            <a href="{{ Storage::url($artwork->image) }}" class="artwork-lightbox" data-lightbox="gallery" data-title="{{ $artwork->name }}">
                <img src="{{ Storage::url($artwork->image) }}" alt="{{ $artwork->name }}" class="w-full h-80 object-cover rounded-lg shadow-xl cursor-pointer transition-transform duration-300">
            </a>

            <!-- Featured Badge -->
            @if($artwork->is_featured)
            <div class="absolute top-4 left-4 text-white font-semibold bg-teal-600 rounded-full px-6 py-2">
                {{ __('Featured Artwork') }}
            </div>
            @endif
        </div>

        <!-- Right Section: Content (Artist Info, Description, Event, Categories) -->
        <div class="flex-1 space-y-6">
            <!-- Artwork Title -->
            <h2 class="text-3xl font-bold text-navy-blue">{{ $artwork->name }}</h2>

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
            <div class="bg-teal-50 p-4 rounded-lg">
                <h3 class="text-xl font-semibold text-teal-600">{{ __('Event') }}</h3>
                @if($artwork->event)
                    <p class="text-gray-700 text-lg">{{ $artwork->event->name }} - <span class="font-semibold text-teal-600">{{ $artwork->event->start_date->format('M d, Y') }} to {{ $artwork->event->end_date->format('M d, Y') }}</span></p>
                @else
                    <p class="text-gray-700 italic">{{ __('No event for this artwork.') }}</p>
                @endif
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
