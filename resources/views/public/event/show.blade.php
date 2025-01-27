@extends('layouts.public')

@section('content')
<section class="my-12">
    <div class="container mx-auto max-w-4xl px-4">
        <!-- Event Title -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-title text-heading">{{ $event->name }}</h1>
            <p class="mt-2 text-lg text-gray-600">{{ __('public/interface.event_subheading') }}</p>
        </div>

        <!-- Event Details -->
        <div class="bg-neutral p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-accent mb-4">{{ __('public/interface.event_details') }}</h2>
            <p class="text-lg text-body mb-4">{{ $event->description }}</p>
            <p class="text-sm text-gray-500 mb-2">
                <strong>{{ __('public/interface.location') }}:</strong> {{ $event->location }}
            </p>
            <p class="text-sm text-gray-500">
                <strong>{{ __('public/interface.date') }}:</strong>
                {{ \Carbon\Carbon::parse($event->start_date)->format('F j, Y') }}
                - {{ \Carbon\Carbon::parse($event->end_date)->format('F j, Y') }}
            </p>
        </div>

        <!-- Call to Action -->
        <div class="text-center mt-8">
            <a
                href="{{ route('events') }}"
                class="inline-block px-8 py-3 bg-accent text-white font-bold rounded-lg shadow-lg hover:bg-cta transition">
                {{ __('public/interface.back_to_events') }}
            </a>
        </div>
    </div>
</section>
@endsection
