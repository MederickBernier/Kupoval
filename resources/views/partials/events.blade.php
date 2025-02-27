<section class="my-16">
    <div class="text-center mb-8">
        <h2 class="text-4xl font-title text-heading">{{ __('public/interface.upcoming_events') }}</h2>
    </div>
    @if(empty($events))
        <div class="text-center">
            <p class="text-body">{{ __('public/interface.no_events') }}</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($events as $event)
            <div class="bg-white shadow-lg rounded-lg overflow-hidden transition-transform transform hover:scale-105 border-t-4 border-accent">
                <article class="p-6">
                    <h5 class="text-2xl font-title mb-3 text-heading relative pb-2 after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-1 after:w-1/4 after:bg-accent">{{ $event['title'] }}</h5>
                    <div class="prose prose-sm text-body mb-4 line-clamp-3 overflow-hidden">
                        <p>{{ $event['description'] }}</p>
                    </div>
                    <div class="mt-4 space-y-2 text-sm">
                        <p class="flex items-center text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <strong>{{ __('public/interface.location') }}:</strong> {{ $event['location'] }}
                        </p>
                        <p class="flex items-center text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <strong>{{ __('public/interface.date') }}:</strong> {{ \Carbon\Carbon::parse($event['date'])->format('F j, Y') }}
                        </p>
                    </div>
                    <div class="mt-6 text-center">
                        <a href="{{ route('event.show',['event' => $event->id]) }}" class="inline-block px-6 py-2 bg-cta text-white rounded-full hover:bg-navbar-hover transition-colors shadow-sm">
                            {{ __('public/interface.learn_more') }}
                        </a>
                    </div>
                </article>
            </div>
            @endforeach
        </div>
    @endif
</section>
