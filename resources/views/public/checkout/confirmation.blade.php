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
            <span class="text-green-600 font-bold">${{ number_format(session('cart_total', 0), 2) }}</span>
        </h3>

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

        <!-- Shipping Selection -->
        <div class="mt-6">
            <label class="block font-bold text-gray-700">Select Shipping:</label>
            <select x-model="shippingConditionId" @change="updateShippingFee"
                    class="border p-2 rounded-md w-1/2 focus:ring focus:ring-blue-200">
                <option value="">Select Shipping</option>
                @foreach($shippingConditions as $condition)
                    <option value="{{ $condition->id }}">{{ $condition->name }} -
                        ${{ number_format($condition->fee, 2) }}</option>
                @endforeach
            </select>
        </div>

        <!-- Total Price -->
        <h3 class="text-xl font-bold text-gray-800 mt-6">Total:
            <span class="text-green-600 font-extrabold">$<span x-text="finalTotal"></span></span>
        </h3>
        <p x-show="discount > 0" class="text-gray-500 text-sm">You saved: $<span x-text="discount"></span></p>

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
            discount: {{ session('discount_amount', 0) }},
            shippingConditionId: null,
            shippingFee: 0,
            subtotal: {{ session('cart_total', 0) }},
            finalTotal: {{ session('cart_total', 0) }},

            applyPromoCode() {
                fetch("{{ route('checkout.applyPromo') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: JSON.stringify({ code: this.promoCode })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.discount = parseFloat(data.discount);
                        this.appliedPromo = this.promoCode;
                        this.promoMessage = "Promo code applied!";
                    } else {
                        this.promoMessage = "Invalid promo code!";
                        this.discount = 0;
                        this.appliedPromo = '';
                    }
                    this.updateTotal();
                });
            },

            removePromoCode() {
                fetch("{{ route('checkout.removePromo') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.discount = 0;
                        this.appliedPromo = '';
                        this.promoCode = '';
                        this.promoMessage = "Promo code removed.";
                    }
                    this.updateTotal();
                });
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
                this.finalTotal = (parseFloat(this.subtotal) - parseFloat(this.discount) + parseFloat(this.shippingFee)).toFixed(2);
            },

            proceedToCheckout() {
                fetch("{{ route('checkout.storeSession') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: JSON.stringify({ final_total: this.finalTotal })
                })
                .then(() => window.location.href = "{{ route('checkout') }}");
            }
        }
    }
</script>
@endsection
