<div class="relative w-full mx-auto my-8">
    <div class="carousel overflow-hidden rounded-lg shadow-lg">
        @foreach ($carouselItems as $index => $item)
            <div class="carousel-item {{ $loop->first ? '' : 'hidden' }}">
                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-full h-64 object-cover">
                <div class="p-4 bg-white bg-opacity-75">
                    <h3 class="text-lg font-bold">{{ $item['title'] }}</h3>
                    <p class="text-sm">{{ $item['description'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Boutons de navigation -->
    <div class="carousel-controls">
        <button id="prev" class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-accent text-white">
            ←
        </button>
        <button id="next" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-accent text-white">
            →
        </button>
    </div>
</div>
