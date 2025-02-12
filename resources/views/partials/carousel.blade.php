<div class="glide my-8">
    <!-- Track -->
    <div class="glide__track" data-glide-el="track">
        <ul class="glide__slides">
            @foreach ($carouselItems as $item)
                <li class="glide__slide">
                    <div class="relative">
                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['title'] }}" class="w-full h-128 object-cover rounded-lg">
                        <div class="absolute bottom-0 left-0 w-full bg-white bg-opacity-80 p-4">
                            <h3 class="text-xl font-title text-heading">{{ $item['title'] }}</h3>
                            <p class="text-sm text-body">{!! $item['description'] !!}</p>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Navigation Arrows -->
    <div class="glide__arrows" data-glide-el="controls">
        <button class="glide__arrow glide__arrow--left" data-glide-dir="<">←</button>
        <button class="glide__arrow glide__arrow--right" data-glide-dir=">">→</button>
    </div>

    <!-- Pagination -->
    <div class="glide__bullets" data-glide-el="controls[nav]">
        @foreach ($carouselItems as $index => $item)
            <button class="glide__bullet" data-glide-dir="={{ $index }}"></button>
        @endforeach
    </div>
</div>
