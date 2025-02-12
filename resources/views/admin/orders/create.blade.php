@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h1 class="text-2xl font-semibold text-gray-800">{{ __('admin/orders.create_order') }}</h1>

    <!-- Alpine.js Data Initialization -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('orderForm', () => ({
                selectedUserId: null,
                selectedUserProfile: {
                    address: '',
                    city: '',
                    state: '',
                    country: '',
                    zipcode: ''
                },
                useDifferentShipping: false,
                selectedArtworks: {},
                totalPrice: 0.00,

                users: @json($users),
                artworks: @json($artworks),

                updateBilling(event) {
                    let userId = event.target.value;
                    this.selectedUserId = userId;

                    // Reset shipping checkbox & selected artworks if user changes
                    if (!userId) {
                        this.useDifferentShipping = false;
                        this.selectedArtworks = {};
                        this.totalPrice = 0.00;
                    }

                    let user = this.users.find(u => u.id == userId);
                    if (user && user.profile) {
                        this.selectedUserProfile = {
                            address: user.profile.address || '',
                            city: user.profile.city || '',
                            state: user.profile.state || '',
                            country: user.profile.country || '',
                            zipcode: user.profile.zipcode || ''
                        };
                    } else {
                        this.selectedUserProfile = { address: '', city: '', state: '', country: '', zipcode: '' };
                    }
                },

                toggleArtwork(id, price) {
                    if (!this.selectedUserId) return;

                    let priceValue = parseFloat(price);
                    if (this.selectedArtworks[id]) {
                        delete this.selectedArtworks[id];
                    } else {
                        this.selectedArtworks[id] = { quantity: 1, price: priceValue };
                    }
                    this.calculateTotalPrice();
                },

                updateQuantity(id, event) {
                    let quantity = parseInt(event.target.value) || 1;
                    if (this.selectedArtworks[id]) {
                        this.selectedArtworks[id].quantity = quantity;
                    }
                    this.calculateTotalPrice();
                },

                calculateTotalPrice() {
                    this.totalPrice = Object.values(this.selectedArtworks)
                        .reduce((sum, item) => sum + (item.quantity * item.price), 0)
                        .toFixed(2);
                }
            }));
        });
    </script>

    <div class="bg-white shadow-md rounded-lg p-6 mt-6" x-data="orderForm">
        <form action="{{ route('admin.orders.store') }}" method="POST">
            @csrf

            <!-- Customer Selection -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/orders.customer') }}</label>
                <select name="user_id" class="w-full border border-gray-300 px-4 py-2 rounded-lg"
                        @change="updateBilling">
                    <option value="">{{ __('admin/orders.select_customer') }}</option>
                    @foreach ($users as $customer)
                        <option value="{{ $customer->id }}">
                            {{ $customer->profile->first_name ?? '' }} {{ $customer->profile->last_name ?? '' }} ({{ $customer->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Artworks Selection -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/orders.artworks') }}</label>

                <div class="max-h-60 overflow-y-auto border border-gray-300 rounded-lg p-2"
                     :class="!selectedUserId ? 'opacity-50 cursor-not-allowed' : ''">
                    @foreach ($artworks as $artwork)
                        <div class="p-2 border-b cursor-pointer flex items-center space-x-3 hover:bg-gray-100"
                             :class="selectedArtworks[{{ $artwork->id }}] ? 'bg-green-200' : ''"
                             @click="selectedUserId && toggleArtwork({{ $artwork->id }}, '{{ $artwork->price }}')">

                            <img src="{{ asset('storage/' . $artwork->image) }}" class="w-12 h-12 object-cover rounded-lg border">

                            <div class="flex flex-col flex-grow">
                                <span class="font-semibold">{{ $artwork->name }}</span>
                                <span class="text-gray-600">${{ number_format($artwork->price, 2) }}</span>
                            </div>

                            <input type="number" min="1" value="1"
                                   class="w-16 border border-gray-300 px-2 py-1 rounded-lg text-sm"
                                   @click.stop
                                   @change="updateQuantity({{ $artwork->id }}, $event)"
                                   :disabled="!selectedUserId">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Hidden Inputs for Selected Artworks -->
            <template x-for="(artwork, id) in selectedArtworks" :key="id">
                <div>
                    <input type="hidden" name="artworks[]" :value="id">
                    <input type="hidden" :name="'quantities[' + id + ']'" :value="artwork.quantity">
                </div>
            </template>

            <!-- Billing Address -->
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-800">{{ __('admin/orders.billing_address') }}</h2>
                <label class="block text-sm font-semibold text-gray-700 mt-1">{{ __('admin/orders.address') }}</label>
                <input type="text" name="billing_address" x-model="selectedUserProfile.address" class="w-full border border-gray-300 px-4 py-2 rounded-lg mt-2" :disabled="!selectedUserId">
                <label class="block text-sm font-semibold text-gray-700 mt-1">{{ __('admin/orders.city') }}</label>
                <input type="text" name="billing_city" x-model="selectedUserProfile.city" class="w-full border border-gray-300 px-4 py-2 rounded-lg mt-2" :disabled="!selectedUserId">
                <label class="block text-sm font-semibold text-gray-700 mt-1">{{ __('admin/orders.state') }}</label>
                <input type="text" name="billing_state" x-model="selectedUserProfile.state" class="w-full border border-gray-300 px-4 py-2 rounded-lg mt-2" :disabled="!selectedUserId">
                <label class="block text-sm font-semibold text-gray-700 mt-1">{{ __('admin/orders.country') }}</label>
                <input type="text" name="billing_country" x-model="selectedUserProfile.country" class="w-full border border-gray-300 px-4 py-2 rounded-lg mt-2" :disabled="!selectedUserId">
                <label class="block text-sm font-semibold text-gray-700 mt-1">{{ __('admin/orders.zipcode') }}</label>
                <input type="text" name="billing_zipcode" x-model="selectedUserProfile.zipcode" class="w-full border border-gray-300 px-4 py-2 rounded-lg mt-2" :disabled="!selectedUserId">
            </div>

            <!-- Shipping Checkbox -->
            <div class="mb-4">
                <input type="hidden" name="use_different_shipping" value="0">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="use_different_shipping" x-model="useDifferentShipping" value="1" :disabled="!selectedUserId">
                    <span>{{ __('admin/orders.use_different_shipping') }}</span>
                </label>
            </div>

            <!-- Shipping Address -->
            <div x-show="useDifferentShipping" x-transition class="mb-4">
                <h2 class="text-lg font-semibold text-gray-800">{{ __('admin/orders.shipping_address') }}</h2>
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/orders.recipient_name') }}</label>
                <input type="text" name="recipient_name" class="w-full border border-gray-300 px-4 py-2 rounded-lg mt-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/orders.recipient_email') }}</label>
                <input type="email" name="recipient_email" class="w-full border border-gray-300 px-4 py-2 rounded-lg mt-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/orders.recipient_phone') }}</label>
                <input type="phone" name="recipient_phone" class="w-full border border-gray-300 px-4 py-2 rounded-lg mt-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/orders.address') }}</label>
                <input type="text" name="shipping_address" class="w-full border border-gray-300 px-4 py-2 rounded-lg mt-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/orders.city') }}</label>
                <input type="text" name="shipping_city" class="w-full border border-gray-300 px-4 py-2 rounded-lg mt-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/orders.state') }}</label>
                <input type="text" name="shipping_state" class="w-full border border-gray-300 px-4 py-2 rounded-lg mt-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/orders.country') }}</label>
                <input type="text" name="shipping_country" class="w-full border border-gray-300 px-4 py-2 rounded-lg mt-2">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/orders.zipcode') }}</label>
                <input type="text" name="shipping_zipcode" class="w-full border border-gray-300 px-4 py-2 rounded-lg mt-2">
            </div>

            <!-- Shipping Condition Selection -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    {{ __('admin/orders.shipping_condition') }}
                </label>
                <select name="shipping_condition_id" class="w-full border border-gray-300 px-4 py-2 rounded-lg"
                        :disabled="!selectedUserId">
                    <option value="">{{ __('admin/orders.select_shipping_condition') }}</option>
                    @foreach ($shippingConditions as $condition)
                        <option value="{{ $condition->id }}">
                            {{ $condition->name }} ({{ $condition->description }}) - ${{ number_format($condition->fee, 2) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Total Price -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/orders.total_price') }}</label>
                <input type="text" name="total_price" readonly x-model="totalPrice" class="w-full border border-gray-300 px-4 py-2 rounded-lg bg-gray-100">
            </div>

            <!-- Submit -->
            <div class="mt-6 flex justify-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">{{ __('admin/orders.create') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
