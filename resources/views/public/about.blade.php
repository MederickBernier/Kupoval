@extends('layouts.public')

@section('content')
<section class="my-12">
    <div class="container mx-auto max-w-4xl px-4">
        <!-- En-tête -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-title text-heading">{{ $page->title }}</h1>
            <p class="mt-2 text-lg text-gray-600">{{ __('public/interface.about_heading') }}</p>
        </div>

        <!-- Contenu Principal -->
        <div class="prose prose-lg mx-auto text-body">
            {!! $page->content !!}
        </div>

        <!-- Section de l'Artiste -->
        @if($artist)
        <div class="my-16 bg-neutral p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-title text-heading mb-4 text-center">{{ __('public/interface.meet_the_artist') }}</h2>
            <div class="flex flex-col md:flex-row items-center">
                <img
                    src="{{ asset($artist->photo) }}"
                    alt="{{ $artist->first_name . ' ' . $artist->last_name }}"
                    class="w-40 h-40 rounded-full object-cover mx-auto mb-6 md:mb-0 md:mr-8 shadow-lg"
                />
                <p class="text-lg text-gray-700">
                    {!! $artist->bio !!}
                </p>
            </div>
        </div>
        @endif

        <!-- Appel à l'Action -->
        <div class="text-center mt-12">
            <a
                href="{{ route('gallery') }}"
                class="inline-block px-8 py-3 bg-accent text-white font-bold rounded-lg shadow-lg hover:bg-cta transition">
                {{ __('public/interface.explore_gallery') }}
            </a>
        </div>
    </div>
</section>
@endsection
