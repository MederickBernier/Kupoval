@extends('layouts.public')

@section('content')
<section class="my-12">
    <div class="container mx-auto max-w-4xl px-4">
        <h1 class="text-4xl font-title text-heading">{{ __('public/interface.bio_heading') }}</h1>
        <p class="mt-2 text-lg text-gray-600">{{ __('public/interface.bio_subheading') }}</p>

        <div class="grid md:grid-cols-2 gap-6 mt-6">
            @foreach($artists as $artist)
                <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition">
                    <div class="flex items-center">
                        <img src="{{ asset($artist->photo) }}" class="w-16 h-16 rounded-full object-cover mr-4">
                        <div>
                            <h2 class="text-lg font-semibold">{{ $artist->first_name }} {{ $artist->last_name }}</h2>
                            <p class="text-gray-600 mt-1">
                                {{ Str::limit(strip_tags($artist->bio), 80) }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 text-right">
                        <a href="{{ route('bio.show', ['artist' => $artist->slug]) }}"
                           class="inline-block px-4 py-2 bg-accent text-white font-bold rounded-lg shadow-lg hover:bg-cta transition">
                            {{ __('public/interface.view_profile') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
