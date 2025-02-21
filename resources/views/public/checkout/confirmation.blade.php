@extends('layouts.public')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-3xl" x-data="checkoutForm()">
    <h2 class="text-2xl font-extrabold text-gray-800 mb-6">{{ __('public/checkout.checkout_confirmation') }}</h2>

    <!-- Cart Review -->
    <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-200">
        <h3 class="text-lg font-bold text-gray-700 mb-3">{{ __('public/checkout.your_cart') }}</h3>
        <div class="border-b pb-4">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-sm font-semibold text-gray-600 uppercase">
                        <th class="p-2">{{ __('public/checkout.item') }}</th>
                        <th class="p-2 text-center">{{ __('public/checkout.quantity') }}</th>
                        <th class="p-2 text-right">{{ __('public/checkout.price') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart->items as $item)
                    <tr class="border-t text-gray-700">
                        <td class="p-2 flex items-center space-x-3">
                            <span>{{ $item->artwork->name }}</span>
                        </td>
                        <td class="p-2 text-center">{{ $item->quantity }}</td>
                        <td class="p-2 text-right">${{ number_format($item->price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h3 class="text-lg font-semibold text-gray-800 mt-4">{{ __('public/checkout.subtotal') }}:
            <span class="text-green-600 font-bold">$<span x-text="subtotal.toFixed(2)"></span></span>
        </h3>

        <!-- Billing Address -->
        <div class="bg-gray-100 p-4 rounded-md shadow-md mt-4">
            <h3 class="text-lg font-semibold text-gray-800">{{ __('public/checkout.billing_address') }}</h3>
            <p class="text-gray-700">
                {{ $billingAddress['address'] ?? 'N/A' }}<br>
                {{ $billingAddress['city'] ?? '' }}, {{ $billingAddress['state'] ?? '' }}<br>
                {{ $billingAddress['country'] ?? '' }} - {{ $billingAddress['zipcode'] ?? '' }}
            </p>
        </div>

        <!-- Shipping Address Section -->
        <div class="bg-gray-100 p-4 rounded-md shadow-md mt-4">
            <h3 class="text-lg font-semibold text-gray-800">{{ __('public/checkout.shipping_address') }}</h3>

            <label class="flex items-center space-x-2">
                <input type="checkbox" x-model="useDifferentShipping">
                <span>{{ __('public/checkout.use_different_shipping') }}</span>
            </label>

            <div x-show="useDifferentShipping && !addingNewAddress" class="mt-4 space-y-2" x-transition>
                <label class="block font-semibold text-gray-700">{{ __('public/checkout.select_shipping_address') }}</label>
                <select x-model="selectedShippingAddress" class="border p-2 rounded-md w-full">
                    <option value="">{{ __('public/checkout.choose_address') }}</option>
                    <template x-for="address in shippingAddresses" :key="address.id">
                        <option :value="address.id" x-text="`${address.address}, ${address.city}, ${address.state}`"></option>
                    </template>
                </select>

                <button type="button"
                        class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
                        @click="addingNewAddress = true">
                    {{ __('public/checkout.add_new_address') }}
                </button>
            </div>

            <!-- New Shipping Address Form -->
            <div x-show="addingNewAddress" class="mt-4 p-4 bg-white border rounded-md shadow" x-transition>
                <h4 class="text-lg font-semibold text-gray-800 mb-3">{{ __('public/checkout.new_shipping_address') }}</h4>

                <input type="text" x-model="newAddress.address" placeholder="{{ __('public/checkout.street_address') }}" class="w-full border p-2 rounded mb-2">
                <input type="text" x-model="newAddress.city" placeholder="{{ __('public/checkout.city') }}" class="w-full border p-2 rounded mb-2">
                <input type="text" x-model="newAddress.state" placeholder="{{ __('public/checkout.state') }}" class="w-full border p-2 rounded mb-2">
                <input type="text" x-model="newAddress.country" placeholder="{{ __('public/checkout.country') }}" class="w-full border p-2 rounded mb-2">
                <input type="text" x-model="newAddress.zipcode" placeholder="{{ __('public/checkout.zipcode') }}" class="w-full border p-2 rounded mb-2">

                <div class="flex justify-end space-x-2 mt-3">
                    <button type="button" class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500" @click="cancelNewAddress">
                        {{ __('public/checkout.cancel') }}
                    </button>
                    <button type="button" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700" @click="saveNewAddress">
                        {{ __('public/checkout.save') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Shipping Selection -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-800">{{ __('public/checkout.shipping_option') }}</h3>
            <select x-model="shippingConditionId" @change="updateShippingFee"
                    class="border p-2 rounded-md w-full focus:ring focus:ring-blue-200">
                <option value="" disabled selected>{{ __('public/checkout.select_shipping') }}</option>
                @foreach($shippingConditions as $condition)
                    <option value="{{ $condition->id }}">
                        {{ $condition->name }} - ${{ number_format($condition->fee, 2) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Promo Code Section -->
        <div class="mt-4">
            <label class="block font-bold text-gray-700 mb-2">{{ __('public/checkout.apply_promo') }}</label>
            <div class="flex items-center space-x-2">
                <input type="text" x-model="promoCode"
                       class="border p-2 rounded-md w-1/3 focus:ring focus:ring-blue-200">
                <button @click="applyPromoCode"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                    {{ __('public/checkout.apply') }}
                </button>
                <button x-show="appliedPromo" @click="removePromoCode"
                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">
                    {{ __('public/checkout.remove') }}
                </button>
            </div>
            <p x-text="promoMessage" class="mt-2 text-sm"
               :class="discount > 0 ? 'text-green-600' : 'text-red-600'"></p>
        </div>

        <!-- Total Price -->
        <h3 class="text-xl font-bold text-gray-800 mt-6">{{ __('public/checkout.total') }}:
            <span class="text-green-600 font-extrabold">$<span x-text="finalTotal.toFixed(2)"></span></span>
        </h3>
        <p x-show="discount > 0" class="text-gray-500 text-sm">{{ __('public/checkout.you_saved') }}: $<span x-text="discount.toFixed(2)"></span></p>

        <!-- Checkout Button -->
        <button @click="proceedToCheckout"
                class="block w-full mt-6 bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-semibold text-lg transition">
            {{ __('public/checkout.proceed_to_payment') }}
        </button>
    </div>
</div>

<script>
function checkoutForm() {
    return {
        // Core variables
        subtotal: parseFloat({{ session('cart_total', 0) }}),
        discount: parseFloat({{ session('discount_amount', 0) }}),
        shippingFee: 0,
        finalTotal: 0,

        // Shipping & Address Logic
        shippingConditionId: "",
        useDifferentShipping: false,
        addingNewAddress: false,
        selectedShippingAddress: "",
        shippingAddresses: @json(Auth::user()->profile->shippingAddresses),
        newAddress: { address: '', city: '', state: '', country: '', zipcode: '' },

        // Promo Code
        promoCode: "",
        appliedPromo: "{{ session('applied_promo', '') }}",
        promoMessage: "",

        // Init function
        init() {
            this.updateTotal();
        },

        // Update total price calculation
        updateTotal() {
            this.finalTotal = this.subtotal - this.discount + this.shippingFee;
        },

        // Cancel new address input
        cancelNewAddress() {
            this.addingNewAddress = false;
        },

        // Save new address
        saveNewAddress() {
            if (!this.newAddress.address || !this.newAddress.city || !this.newAddress.zipcode) {
                alert("Please fill in all required fields.");
                return;
            }
            this.shippingAddresses.push({...this.newAddress});
            this.newAddress = { address: '', city: '', state: '', country: '', zipcode: '' };
            this.addingNewAddress = false;
        },

        // Update shipping fee
        updateShippingFee() {
            if (!this.shippingConditionId) {
                this.shippingFee = 0;
                this.updateTotal();
                return;
            }
            fetch("{{ route('checkout.updateShipping') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ shipping_id: this.shippingConditionId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.shippingFee = parseFloat(data.shipping_fee);
                    this.updateTotal();
                }
            })
            .catch(error => console.error("Error updating shipping:", error));
        },

        // Apply promo code
        applyPromoCode() {
            if (!this.promoCode) {
                this.promoMessage = "Please enter a promo code.";
                return;
            }
            fetch("{{ route('checkout.applyPromo') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ code: this.promoCode })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.discount = parseFloat(data.discount);
                    this.appliedPromo = data.code;
                    this.promoMessage = `Promo applied successfully! You saved $${this.discount.toFixed(2)} (${((this.discount / this.subtotal) * 100).toFixed(2)}% off)`;
                } else {
                    this.discount = 0;
                    this.promoMessage = "Invalid promo code.";
                }
                this.updateTotal();
            })
            .catch(error => console.error("Error applying promo:", error));
        },

        // Remove promo code
        removePromoCode() {
            fetch("{{ route('checkout.removePromo') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" }
            })
            .then(() => {
                this.discount = 0;
                this.appliedPromo = '';
                this.promoMessage = '';
                this.updateTotal();
            })
            .catch(error => console.error("Error removing promo:", error));
        },

        // Proceed to checkout
        proceedToCheckout() {
            fetch("{{ route('checkout.storeSession') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ final_total: this.finalTotal })
            })
            .then(() => window.location.href = "{{ route('checkout') }}")
            .catch(error => console.error("Error proceeding to checkout:", error));
        }
    };
}
</script>
@endsection
