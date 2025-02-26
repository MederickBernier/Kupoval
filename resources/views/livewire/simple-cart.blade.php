<div>
    <!-- Floating Cart Button - Fixed at bottom of viewport on mobile, normal on desktop -->
    @if($cartItemCount > 0)
        <button wire:click="toggleCart"
            class="fixed bottom-0 left-0 right-0 md:bottom-4 md:right-4 md:left-auto bg-teal-600 hover:bg-teal-700 text-white p-4 md:rounded-full shadow-lg transition-transform hover:scale-105 flex items-center justify-center z-50 md:w-auto w-full">
            <i class="bi bi-cart text-2xl"></i>
            <span class="ml-2 font-bold">{{ $cartItemCount }}</span>
        </button>
    @endif

    <!-- Overlay (Click Outside to Close) -->
    @if($showCart)
        <div class="fixed inset-0 bg-black bg-opacity-30 z-40" wire:click="toggleCart"></div>
    @endif

    <!-- Sliding Cart Panel - Full width on mobile, normal on desktop -->
    <div class="fixed top-0 right-0 h-screen w-full md:w-96 bg-white shadow-2xl border-l border-gray-300 z-50 transform transition-transform duration-300 ease-in-out {{ $showCart ? 'translate-x-0' : 'translate-x-full' }}">
        <div class="p-4 flex justify-between items-center bg-gray-100 border-b sticky top-0">
            <h3 class="text-lg font-bold">🛒 {{ __('public/cart.your_cart') }}</h3>
            <button wire:click="toggleCart" class="text-red-600 hover:text-red-800 text-2xl font-bold">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        </div>

        <div class="p-4 overflow-y-auto" style="height: calc(100vh - 72px);">
            @if($cartItemCount > 0)
                <ul class="divide-y">
                    @foreach($cartItems as $id => $item)
                        <li class="py-3 grid grid-cols-[1fr_auto_auto] sm:grid-cols-[1fr_auto_auto] items-center gap-2 sm:gap-4">
                            <!-- Item Name & Price -->
                            <div class="pr-2 sm:pr-4">
                                <span class="block font-semibold text-gray-900 text-sm sm:text-base">{{ $item['name'] }}</span>
                                <span class="text-gray-600 text-xs sm:text-sm">{{ number_format($item['price'], 2) }} $ / {{ __('public/cart.per_unit') }}</span>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center space-x-1 sm:space-x-2">
                                <button wire:click="decrementQuantity({{ $id }})"
                                        class="bg-gray-300 px-2 sm:px-3 py-1 rounded hover:bg-gray-400 text-sm sm:text-base">−</button>

                                <span class="text-base sm:text-lg font-bold">{{ $item['quantity'] }}</span>

                                <button wire:click="incrementQuantity({{ $id }})"
                                        class="bg-gray-300 px-2 sm:px-3 py-1 rounded hover:bg-gray-400 text-sm sm:text-base">+</button>
                            </div>

                            <!-- Remove Button (Aligned Right) -->
                            <button wire:click="removeFromCart({{ $id }})"
                                    class="text-red-600 hover:text-red-800 text-lg sm:text-xl ml-auto">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-4 text-right border-t pt-3">
                    <span class="text-lg font-bold">{{ __('public/cart.total') }}: {{ number_format($totalPrice, 2) }} $</span>
                </div>

                <!-- Checkout Button - Sticky at bottom on mobile -->
                <div class="sticky bottom-0 left-0 right-0 p-4 bg-white border-t mt-4">
                    <button x-data @click="window.location.href='{{ route('checkout.confirmation') }}'"
                        class="block w-full bg-teal-600 hover:bg-teal-700 text-white py-3 sm:py-2 rounded-lg font-semibold text-center text-lg">
                        {{ __('public/cart.confirm_order') }}
                    </button>
                </div>
            @else
                <p class="text-gray-500 text-center mt-4">{{ __('public/cart.empty_cart') }}</p>
            @endif
        </div>
    </div>

    <!-- Extra padding at the bottom on mobile to account for fixed cart button -->
    @if($cartItemCount > 0)
        <div class="block md:hidden h-16"></div>
    @endif
</div>
