@extends('layouts.public')

@section('content')
    <section class="my-12">
        <div class="container mx-auto max-w-6xl px-4">
            <!-- Artist Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-title text-heading mb-4">{{ $artist->first_name }}
                    {{ $artist->last_name }}</h1>
                @if ($artist->studio_location)
                    <p class="text-lg text-gray-600 mb-2">📍 {{ $artist->studio_location }}</p>
                @endif
                @if ($artist->experience_years)
                    <p class="text-sm text-gray-500">{{ $artist->experience_years }}
                        {{ __('public/artists.years_experience') }}</p>
                @endif
            </div>

            <!-- Main Profile Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                <!-- Profile Image and Quick Info -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-8">
                        <img src="{{ asset($artist->photo) }}" alt="{{ $artist->first_name . ' ' . $artist->last_name }}"
                            class="w-full aspect-square rounded-xl object-cover mb-6 shadow-md">

                        @if ($artist->specialties && count($artist->specialties) > 0)
                            <div class="mb-4">
                                <h3 class="font-semibold text-gray-800 mb-2">{{ __('public/artists.specialties') }}</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($artist->specialties as $specialty)
                                        <span
                                            class="px-3 py-1 bg-teal-100 text-teal-800 text-sm rounded-full">{{ $specialty }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($artist->techniques && count($artist->techniques) > 0)
                            <div class="mb-6">
                                <h3 class="font-semibold text-gray-800 mb-2">{{ __('public/artists.techniques') }}</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($artist->techniques as $technique)
                                        <span
                                            class="px-3 py-1 bg-emerald-100 text-emerald-800 text-sm rounded-full">{{ $technique }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Contact & Social Links -->
                        <div class="space-y-3">
                            @if ($artist->email)
                                <a href="mailto:{{ $artist->email }}"
                                    class="flex items-center text-gray-600 hover:text-teal-600 transition">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                        </path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                    {{ __('public/artists.email') }}
                                </a>
                            @endif

                            @if ($artist->website)
                                <a href="{{ $artist->website }}" target="_blank"
                                    class="flex items-center text-gray-600 hover:text-teal-600 transition">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    {{ __('public/artists.website') }}
                                </a>
                            @endif

                            <!-- Social Media Icons -->
                            <div class="flex space-x-3 pt-2">
                                @if ($artist->facebook)
                                    <a href="{{ $artist->facebook }}" target="_blank"
                                        class="text-gray-400 hover:text-blue-600 transition">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                        </svg>
                                    </a>
                                @endif
                                @if ($artist->instagram)
                                    <a href="{{ $artist->instagram }}" target="_blank"
                                        class="text-gray-400 hover:text-pink-600 transition">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987s11.987-5.367 11.987-11.987C24.014 5.367 18.647.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.596-3.205-1.539L12.017 8.68l6.773 6.769c-.757.943-1.908 1.539-3.205 1.539H8.449z" />
                                        </svg>
                                    </a>
                                @endif
                                @if ($artist->twitter)
                                    <a href="{{ $artist->twitter }}" target="_blank"
                                        class="text-gray-400 hover:text-blue-400 transition">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                        </svg>
                                    </a>
                                @endif
                                @if ($artist->youtube)
                                    <a href="{{ $artist->youtube }}" target="_blank"
                                        class="text-gray-400 hover:text-red-600 transition">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Contact Button -->
                        <div class="mt-6 pt-6 border-t">
                            <a href="{{ route('contact.artist.form', ['artist' => $artist->slug]) }}"
                                class="w-full block text-center px-6 py-3 bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-semibold rounded-lg shadow-md hover:from-teal-700 hover:to-emerald-700 transition duration-200">
                                {{ __('public/interface.contact_artist') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Biography -->
                    @if ($artist->bio)
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">{{ __('public/artists.biography') }}</h2>
                            <div class="prose prose-lg text-body max-w-none">
                                {!! nl2br($artist->bio) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Artist Statement -->
                    @if ($artist->artist_statement)
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                                {{ __('public/artists.artist_statement') }}</h2>
                            <div class="prose prose-lg text-body max-w-none">
                                {!! nl2br($artist->artist_statement) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Profile Video -->
                    @if ($artist->profile_video_url)
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">{{ __('public/artists.profile_video') }}
                            </h2>
                            <div class="aspect-video">
                                <iframe src="{{ str_replace('watch?v=', 'embed/', $artist->profile_video_url) }}"
                                    class="w-full h-full rounded-lg" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                            </div>
                        </div>
                    @endif

                    <!-- Exhibition History -->
                    @if ($artist->exhibition_history)
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">
                                {{ __('public/artists.exhibition_history') }}</h2>
                            <div class="prose prose-lg text-body max-w-none">
                                {!! nl2br(str_replace('\\n', "\n", $artist->exhibition_history)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Awards & Recognition -->
                    @if ($artist->awards)
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h2 class="text-2xl font-semibold text-gray-800 mb-4">{{ __('public/artists.awards') }}</h2>
                            <div class="prose prose-lg text-body max-w-none">
                                {!! nl2br(str_replace('\\n', "\n", $artist->awards)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Artist's Artworks -->
            @if ($artist->artworks->count() > 0)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
                        {{ __('public/artists.artworks_by', ['name' => $artist->first_name]) }}</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($artist->artworks->take(6) as $artwork)
                            <div class="group">
                                <a href="{{ route('artwork.show', $artwork->slug) }}" class="block">
                                    <div
                                        class="aspect-square overflow-hidden rounded-lg shadow-md group-hover:shadow-xl transition duration-300">
                                        <img src="{{ asset('storage/' . $artwork->image) }}" alt="{{ $artwork->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    </div>
                                    <div class="mt-3">
                                        <h3 class="font-semibold text-gray-800 group-hover:text-teal-600 transition">
                                            {{ $artwork->name }}</h3>
                                        <p class="text-sm text-gray-600">${{ number_format($artwork->initial_price, 2) }}
                                        </p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    @if ($artist->artworks->count() > 6)
                        <div class="text-center mt-8">
                            <a href="{{ route('shop.index', ['artist' => $artist->slug]) }}"
                                class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition">
                                {{ __('public/artists.view_all_artworks') }}
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>
@endsection
