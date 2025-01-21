<div class="relative w-full mx-auto my-8">
    <div class="carousel overflow-hidden rounded-lg shadow-emerald">
        @foreach ($carouselItems as $index => $item)
            <div class="carousel-item {{ $loop->first ? '' : 'hidden' }} transition-opacity duration-700 ease-in-out">
                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['title'] }}" class="w-full h-96 object-cover">
                <div class="p-4 bg-white bg-opacity-80 shadow-lg">
                    <h3 class="text-xl font-title text-heading">{{ $item['title'] }}</h3>
                    <p class="text-sm text-body">{{ $item['description'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Navigation Buttons -->
    <div class="carousel-controls">
        <button id="prev" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-accent text-white rounded-full px-3 py-1 shadow-lg hover:bg-navbar-hover transition">
            ←
        </button>
        <button id="next" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-accent text-white rounded-full px-3 py-1 shadow-lg hover:bg-navbar-hover transition">
            →
        </button>
    </div>

    <!-- Indicators -->
    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
        @foreach ($carouselItems as $index => $item)
            <button class="carousel-indicator w-3 h-3 rounded-full {{ $loop->first ? 'bg-accent' : 'bg-neutral' }}" data-index="{{ $index }}"></button>
        @endforeach
    </div>
</div>
