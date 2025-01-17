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
            <div class="bg-white shadow-lg rounded-lg overflow-hidden transition-transform transform hover:scale-105">
                <article class="p-6">
                    <h5 class="text-xl font-bold mb-2 text-accent">{{ $event->title }}</h5>
                    <p class="text-sm text-body mb-4">{{ $event->description }}</p>
                    <p class="text-sm text-gray-500">
                        <strong>{{ __('public/interface.location') }}:</strong> {{ $event->location }}
                    </p>
                    <p class="text-sm text-gray-500">
                        <strong>{{ __('public/interface.date') }}:</strong> {{ \Carbon\Carbon::parse($event->date)->format('F j, Y') }}
                    </p>
                    <a href="#" class="inline-block mt-4 px-6 py-2 bg-cta text-white rounded-full hover:bg-navbar-hover">
                        {{ __('public/interface.learn_more') }}
                    </a>
                </article>
            </div>
            @endforeach
        </div>
        @endif
</section>
