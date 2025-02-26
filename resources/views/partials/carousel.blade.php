<div class="glide my-8 relative">
    <!-- Track -->
    <div class="glide__track" data-glide-el="track">
        <ul class="glide__slides">
            @foreach ($carouselItems as $item)
                <li class="glide__slide">
                    <div class="relative">
                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-128 object-cover rounded-lg">
                        <div class="absolute bottom-0 left-0 w-full bg-white bg-opacity-80 p-4">
                            <h3 class="text-xl font-title text-heading">{{ $item['name'] }}</h3>
                            <p class="text-sm text-body mb-6">
                                {!! Str::limit($item['description'], 120) !!}
                            </p>

                            <!-- Pagination - centered within the text overlay -->
                            <div class="flex justify-center pb-2">
                                <div class="glide__bullets flex space-x-2" data-glide-el="controls[nav]">
                                    @foreach ($carouselItems as $index => $item)
                                        <button class="glide__bullet w-3 h-3 rounded-full bg-gray-400 hover:bg-gray-600 focus:bg-gray-600" data-glide-dir="={{ $index }}"></button>
                                    @endforeach
                                </div>
                            </div>
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
</div>
