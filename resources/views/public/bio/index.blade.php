@extends('layouts.public')

@section('content')
<section class="my-12">
    <div class="container mx-auto max-w-5xl px-4">
        <h1 class="text-4xl font-title text-heading text-center">{{ __('public/interface.bio_heading') }}</h1>
        <p class="mt-2 text-lg text-gray-600 text-center">{{ __('public/interface.bio_subheading') }}</p>

        <div class="flex flex-col space-y-8 mt-10" x-data="{ animate: false }" x-init="setTimeout(() => animate = true, 200)">
            @foreach($artists as $index => $artist)
                <div
                    class="flex flex-col md:flex-row {{ $index % 2 == 0 ? 'md:flex-row-reverse' : '' }} bg-white shadow-lg rounded-lg p-6 hover:shadow-2xl transition-all duration-300 items-center transform scale-95 hover:scale-100 opacity-0"
                    :class="{ 'opacity-100 translate-y-0': animate }"
                    x-transition:enter="transition-opacity ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-5"
                    x-transition:enter-end="opacity-100 translate-y-0"
                >

                    <!-- Artist Image -->
                    <div class="w-32 h-32 md:w-40 md:h-40 flex-shrink-0 transition-transform duration-300 transform hover:scale-110">
                        <img src="{{ asset($artist->photo) }}" class="rounded-lg object-cover shadow-lg w-full h-full">
                    </div>

                    <!-- Artist Details -->
                    <div class="ml-6 flex-1 text-center md:text-left">
                        <h2 class="text-2xl font-semibold text-gray-900">{{ $artist->first_name }} {{ $artist->last_name }}</h2>
                        <p class="text-gray-600 mt-2 leading-relaxed">
                            {{ Str::limit(strip_tags($artist->bio), 120) }}
                        </p>
                        <div class="mt-4 text-center md:text-left">
                            <a href="{{ route('bio.show', ['artist' => $artist->slug]) }}"
                               class="inline-block px-6 py-2 bg-accent text-white font-bold rounded-lg shadow-lg hover:bg-cta transition-all duration-300 transform hover:-translate-y-1">
                                {{ __('public/interface.view_profile') }}
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
