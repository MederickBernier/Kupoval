@extends('layouts.public')

@section('content')
    <div x-data="{ openContactModal: false }" class="p-6 page-background text-charcoal-gray min-h-screen">
        <div class="max-w-6xl mx-auto artwork-card p-8 grid lg:grid-cols-2 gap-8">

            <!-- Left Section: Artwork Image -->
            <div class="artwork-frame">
                <div class="artwork-matting">
                    <a href="{{ Storage::url($artwork->image) }}" class="artwork-lightbox" data-lightbox="gallery"
                        data-title="{{ $artwork->name }}">
                        <img src="{{ Storage::url($artwork->image) }}" alt="{{ $artwork->name }}"
                            class="artwork-image w-full h-80 object-cover cursor-pointer transition-transform duration-300">
                    </a>
                </div>

                <!-- Status Badges -->
                <div class="absolute top-4 left-4 flex flex-col space-y-1">
                    @if ($artwork->is_featured)
                        <span class="bg-teal-600 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center">
                            <i class="bi bi-star-fill mr-1"></i> {{ __('public/artwork.featured') }}
                        </span>
                    @endif
                    @if ($artwork->is_on_sale)
                        <span class="bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center">
                            <i class="bi bi-tag-fill mr-1"></i> {{ __('public/artwork.for_sale') }}
                        </span>
                    @endif
                    @if ($artwork->is_for_event && $artwork->event)
                        <span class="bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full flex items-center">
                            <i class="bi bi-calendar-event-fill mr-1"></i> {{ __('public/artwork.event_exclusive') }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Right Section: Content (Details, Description, Event, etc.) -->
            <div class="space-y-6">
                <h2 class="text-3xl font-bold text-navy-blue">{{ $artwork->name }}</h2>

                <div class="bg-teal-50 p-4 rounded-lg">
                    <h3 class="text-xl font-semibold text-teal-600">{{ __('public/artwork.details') }}</h3>
                    <ul class="text-gray-700 list-disc list-inside mt-2">
                        <li><strong>{{ __('public/artwork.dimensions') }}:</strong> {{ $artwork->width }} cm ×
                            {{ $artwork->height }} cm</li>
                    </ul>
                </div>

                <div class="bg-teal-50 p-4 rounded-lg">
                    <h3 class="text-xl font-semibold text-teal-600">{{ __('public/artwork.description') }}</h3>
                    <p class="text-gray-700">{!! $artwork->description !!}</p>
                </div>

                <div class="bg-teal-50 p-4 rounded-lg">
                    <h3 class="text-xl font-semibold text-teal-600">{{ __('public/artwork.categories') }}</h3>
                    @if ($artwork->categories->isNotEmpty())
                        <div class="flex flex-wrap gap-3 mt-4">
                            @foreach ($artwork->categories as $category)
                                <span class="bg-teal-600 text-white px-6 py-2 rounded-full">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-700 italic">{{ __('public/artwork.no_categories') }}</p>
                    @endif
                </div>

                @if ($artwork->event)
                    <div class="bg-teal-50 p-4 rounded-lg">
                        <h3 class="text-xl font-semibold text-teal-600">{{ __('public/artwork.event_details') }}</h3>
                        <p class="text-gray-700">
                            <strong>{{ $artwork->event->name }}</strong> <br>
                            {{ __('public/artwork.from') }} <span
                                class="font-semibold text-teal-600">{{ $artwork->event->start_date->format('M d, Y') }}</span>
                            {{ __('public/artwork.to') }} <span
                                class="font-semibold text-teal-600">{{ $artwork->event->end_date->format('M d, Y') }}</span>
                        </p>
                        @if ($artwork->event->location)
                            <p class="mt-2">
                                <strong>{{ __('public/artwork.location') }}:</strong> {{ $artwork->event->location }}
                            </p>
                        @endif
                        <p class="mt-2 text-sm text-gray-600">
                            {{ $artwork->event->description }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Artist Section (Spanning 2 Columns) -->
            <div class="bg-teal-50 p-4 rounded-lg lg:col-span-2">
                <h3 class="text-xl font-semibold text-teal-600">{{ __('public/artwork.artist') }}</h3>
                <div class="flex items-center space-x-6 mt-4">
                    <img src="{{ asset($artwork->artist->photo) }}" alt="{{ $artwork->artist->name }}"
                        class="w-24 h-24 object-cover rounded-full border-4 border-deep-emerald shadow-md">
                    <div>
                        <p class="text-gray-800 text-lg">{{ $artwork->artist->name }}</p>
                        <p class="text-gray-600 text-sm italic mt-2">{{ $artwork->artist->bio }}</p>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <a href="{{ route('contact.artist.form', ['artist' => $artwork->artist->slug]) }}"
                        class="bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white px-6 py-3 rounded-full font-medium transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="bi bi-envelope-fill"></i>
                        <span>{{ __('public/artwork.contact_artist') }}</span>
                    </a>
                </div>
            </div>

            <!-- Back to Gallery Button -->
            <div class="lg:col-span-2 mt-8 flex justify-center">
                <a href="{{ route('gallery') }}"
                    class="bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white px-8 py-4 rounded-full text-lg font-medium transition-all duration-200 flex items-center gap-3 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="bi bi-arrow-left"></i>
                    <span>{{ __('public/artwork.back_to_gallery') }}</span>
                </a>
            </div>

        </div>
    </div>
@endsection
