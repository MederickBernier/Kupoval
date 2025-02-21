<div>
    <!-- Floating Cart Button -->
    @if($cartItemCount > 0)
        <button wire:click="toggleCart"
            class="fixed bottom-4 right-4 bg-teal-600 hover:bg-teal-700 text-white p-4 rounded-full shadow-lg transition-transform hover:scale-105 flex items-center z-50">
            <i class="bi bi-cart text-2xl"></i>
            <span class="ml-2 font-bold">{{ $cartItemCount }}</span>
        </button>
    @endif

    <!-- Overlay (Click Outside to Close) -->
    @if($showCart)
        <div class="fixed inset-0 bg-black bg-opacity-30 z-40" wire:click="toggleCart"></div>
    @endif

    <!-- Sliding Cart Panel -->
    <div class="fixed top-0 right-0 h-screen w-96 bg-white shadow-2xl border-l border-gray-300 z-50 transform transition-transform duration-300 ease-in-out {{ $showCart ? 'translate-x-0' : 'translate-x-full' }}">
        <div class="p-4 flex justify-between items-center bg-gray-100 border-b">
            <h3 class="text-lg font-bold">🛒 {{ __('public/cart.your_cart') }}</h3>
            <button wire:click="toggleCart" class="text-red-600 hover:text-red-800 text-2xl font-bold">
                <i class="bi bi-x-circle-fill"></i>
            </button>
        </div>

        <div class="p-4 overflow-y-auto h-full">
            @if($cartItemCount > 0)
                <ul class="divide-y">
                    @foreach($cartItems as $id => $item)
                        <li class="py-3 grid grid-cols-[1fr_auto_auto] items-center gap-4">
                            <!-- Item Name & Price -->
                            <div class="pr-4">
                                <span class="block font-semibold text-gray-900">{{ $item['name'] }}</span>
                                <span class="text-gray-600 text-sm">{{ number_format($item['price'], 2) }} $ / {{ __('public/cart.per_unit') }}</span>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center space-x-2">
                                <button wire:click="decrementQuantity({{ $id }})"
                                        class="bg-gray-300 px-3 py-1 rounded hover:bg-gray-400">−</button>

                                <span class="text-lg font-bold">{{ $item['quantity'] }}</span>

                                <button wire:click="incrementQuantity({{ $id }})"
                                        class="bg-gray-300 px-3 py-1 rounded hover:bg-gray-400">+</button>
                            </div>

                            <!-- Remove Button (Aligned Right) -->
                            <button wire:click="removeFromCart({{ $id }})"
                                    class="text-red-600 hover:text-red-800 text-xl ml-auto">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-4 text-right border-t pt-3">
                    <span class="text-lg font-bold">{{ __('public/cart.total') }}: {{ number_format($totalPrice, 2) }} $</span>
                </div>

                <!-- ✅ Stripe Checkout Button -->
                <button x-data @click="window.location.href='{{ route('checkout.confirmation') }}'"
                    class="block w-full mt-4 bg-teal-600 hover:bg-teal-700 text-white py-2 rounded-lg font-semibold text-center">
                    {{ __('public/cart.confirm_order') }}
                </button>
            @else
                <p class="text-gray-500 text-center mt-4">{{ __('public/cart.empty_cart') }}</p>
            @endif
        </div>
    </div>
</div>
