@extends('layouts.public')

@section('content')

<section class="my-8">
    <h1 class="text-4xl font-title text-heading text-center mb-8">{{ __('public/events.upcoming_events') }}</h1>

    @if($events->isEmpty())
        <p class="text-center text-body">{{ __('public/events.no_upcoming') }}</p>
    @else
        <div class="w-full max-w-4xl mx-auto">
            @foreach ($events as $month => $cur_events)
                <!-- Month Separator -->
                <div class="flex items-center justify-center my-8">
                    <div class="border-t flex-grow border-gray-300"></div>
                    <h2 class="px-4 py-2 bg-neutral text-2xl font-bold text-accent rounded-lg shadow-md">
                        {{ __( 'public/events.months.' . strtolower(\Carbon\Carbon::parse($cur_events->first()->start_date)->translatedFormat('F'))) }}
                    </h2>
                    <div class="border-t flex-grow border-gray-300"></div>
                </div>

                <!-- List of Events -->
                <ul class="space-y-6">
                    @foreach ($cur_events as $event)
                        <li class="relative pl-8 border-l-4 border-accent">
                            <div class="absolute -left-4 top-1/2 transform -translate-y-1/2 w-8 h-8 bg-accent rounded-full shadow-md flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</span>
                            </div>
                            <div class="ml-4">
                                <p class="text-lg font-bold text-heading">{{ $event->name }}</p>
                                <p class="text-sm text-body mb-2">{{ $event->description }}</p>
                                <p class="text-sm text-gray-500">
                                    <strong>{{ __('public/events.location') }}:</strong> {{ $event->location }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    <strong>{{ __('public/events.date') }}:</strong>
                                    {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('F j, Y') }}
                                    - {{ \Carbon\Carbon::parse($event->end_date)->translatedFormat('F j, Y') }}
                                </p>
                                <!-- View Details Button -->
                                <a href="{{ route('event.show', ['event' => $event->id]) }}"
                                   class="inline-block mt-4 px-6 py-2 bg-accent text-white font-bold rounded-full hover:bg-cta transition">
                                    {{ __('public/events.view_details') }}
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    @endif
</section>
@endsection
