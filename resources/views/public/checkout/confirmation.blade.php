@extends('layouts.public')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-3xl" x-data="checkoutForm()">
    <h2 class="text-2xl font-extrabold text-gray-800 mb-6">Checkout Confirmation</h2>

    <!-- Cart Review -->
    <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-200">
        <h3 class="text-lg font-bold text-gray-700 mb-3">Your Cart</h3>
        <div class="border-b pb-4">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-sm font-semibold text-gray-600 uppercase">
                        <th class="p-2">Item</th>
                        <th class="p-2 text-center">Quantity</th>
                        <th class="p-2 text-right">Price</th>
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

        <h3 class="text-lg font-semibold text-gray-800 mt-4">Subtotal:
            <span class="text-green-600 font-bold">$<span x-text="subtotal.toFixed(2)"></span></span>
        </h3>

        <!-- Billing Address -->
        <div class="bg-gray-100 p-4 rounded-md shadow-md mt-4">
            <h3 class="text-lg font-semibold text-gray-800">Billing Address</h3>
            <p class="text-gray-700">
                {{ $billingAddress['address'] ?? 'N/A' }}<br>
                {{ $billingAddress['city'] ?? '' }}, {{ $billingAddress['state'] ?? '' }}<br>
                {{ $billingAddress['country'] ?? '' }} - {{ $billingAddress['zipcode'] ?? '' }}
            </p>
        </div>

        <!-- Shipping Address (Now as Editable Fields) -->
        <div class="bg-gray-100 p-4 rounded-md shadow-md mt-4">
            <h3 class="text-lg font-semibold text-gray-800">Shipping Address</h3>

            <label class="flex items-center space-x-2">
                <input type="checkbox" id="use_different_shipping" x-model="useDifferentShipping">
                <span>Use a different shipping address</span>
            </label>

            <div x-show="useDifferentShipping" class="mt-4 space-y-2" x-transition>
                <input type="text" x-model="shippingAddress.recipient_name" placeholder="Recipient Name" class="w-full border p-2 rounded">
                <input type="email" x-model="shippingAddress.recipient_email" placeholder="Recipient Email" class="w-full border p-2 rounded">
                <input type="text" x-model="shippingAddress.recipient_phone" placeholder="Recipient Phone" class="w-full border p-2 rounded">
                <input type="text" x-model="shippingAddress.address" placeholder="Street Address" class="w-full border p-2 rounded">
                <input type="text" x-model="shippingAddress.city" placeholder="City" class="w-full border p-2 rounded">
                <input type="text" x-model="shippingAddress.state" placeholder="State" class="w-full border p-2 rounded">
                <input type="text" x-model="shippingAddress.country" placeholder="Country" class="w-full border p-2 rounded">
                <input type="text" x-model="shippingAddress.zipcode" placeholder="Zip Code" class="w-full border p-2 rounded">
            </div>
        </div>

        <!-- Shipping Selection -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-800">Shipping Option</h3>
            <select x-model="shippingConditionId" @change="updateShippingFee"
                    class="border p-2 rounded-md w-full focus:ring focus:ring-blue-200">
                <option value="">Select Shipping</option>
                @foreach($shippingConditions as $condition)
                    <option value="{{ $condition->id }}">
                        {{ $condition->name }} - ${{ number_format($condition->fee, 2) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Promo Code Section -->
        <div class="mt-4">
            <label class="block font-bold text-gray-700 mb-2">Apply Promo Code:</label>
            <div class="flex items-center space-x-2">
                <input type="text" x-model="promoCode"
                       class="border p-2 rounded-md w-1/3 focus:ring focus:ring-blue-200">
                <button @click="applyPromoCode"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">Apply</button>
                <button x-show="appliedPromo" @click="removePromoCode"
                        class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">Remove</button>
            </div>
            <p x-text="promoMessage" class="mt-2 text-sm"
               :class="discount > 0 ? 'text-green-600' : 'text-red-600'"></p>
        </div>

        <!-- Total Price -->
        <h3 class="text-xl font-bold text-gray-800 mt-6">Total:
            <span class="text-green-600 font-extrabold">$<span x-text="finalTotal.toFixed(2)"></span></span>
        </h3>
        <p x-show="discount > 0" class="text-gray-500 text-sm">You saved: $<span x-text="discount.toFixed(2)"></span></p>

        <!-- Checkout Button -->
        <button @click="proceedToCheckout"
                class="block w-full mt-6 bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-semibold text-lg transition">
            {{ __('Proceed to Payment') }}
        </button>
    </div>
</div>

<script>
function checkoutForm() {
    return {
        promoCode: '',
        appliedPromo: "{{ session('applied_promo', '') }}",
        promoMessage: '',
        discount: 0, // Reset discount to avoid pre-applied reductions
        shippingConditionId: "{{ session('shipping_condition_id', '') }}",
        shippingFee: parseFloat({{ session('shipping_fee', 0) }}),
        subtotal: parseFloat({{ session('cart_total', 0) }}),
        finalTotal: 0.00,
        useDifferentShipping: false,
        shippingAddress: {
            recipient_name: '',
            recipient_email: '',
            recipient_phone: '',
            address: '',
            city: '',
            state: '',
            country: '',
            zipcode: ''
        },

        updateShippingFee() {
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
            });
        },

        updateTotal() {
            this.finalTotal = (this.subtotal - this.discount + this.shippingFee);
        },

        proceedToCheckout() {
            fetch("{{ route('checkout.storeSession') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ final_total: this.finalTotal, shipping_address: this.shippingAddress })
            })
            .then(() => window.location.href = "{{ route('checkout') }}");
        }
    };
}
</script>
@endsection
