@extends('layouts.public')

@section('content')
<section class="my-12">
    <div class="container mx-auto max-w-4xl px-4">
        <!-- En-tête -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-title text-heading">{{ __('public/interface.bio_heading') }}</h1>
            <p class="mt-2 text-lg text-gray-600">{{ __('public/interface.bio_subheading') }}</p>
        </div>

        <!-- Contenu Principal -->
        <div class="flex flex-col md:flex-row items-center mb-12">
            <img
                src="{{ asset($artist->photo) }}"
                alt="{{ $artist->first_name . ' ' . $artist->last_name }}"
                class="w-48 h-48 rounded-full object-cover mx-auto mb-6 md:mb-0 md:mr-8 shadow-lg"
            />
            <div class="prose prose-lg text-body">
                {!! $artist->bio !!}
            </div>
        </div>

        <!-- Appel à l'Action -->
        <div class="text-center">
            <a
                href="{{ route('contact') }}"
                class="inline-block px-8 py-3 bg-accent text-white font-bold rounded-lg shadow-lg hover:bg-cta transition">
                {{ __('public/interface.contact_artist') }}
            </a>
        </div>
    </div>
</section>
@endsection
