@extends('layouts.admin')

@section('title', __('admin/orders.edit_order'))
@section('page-title', __('admin/orders.edit_order'))

@section('content')
<div class="container mx-auto p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-semibold mb-4">
        <i class="bi bi-pencil-square"></i> {{ __('admin/orders.edit_order') }} #{{ $order->id }}
    </h2>

    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-6">
            <!-- Customer Selection -->
            <div>
                <label class="block font-semibold">{{ __('admin/orders.customer') }}</label>
                <select name="user_id" class="w-full border px-4 py-2 rounded-lg">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $order->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->profile->first_name ?? $user->name }} {{ $user->profile->last_name ?? '' }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Order Status -->
            <div>
                <label class="block font-semibold">{{ __('admin/orders.status') }}</label>
                <select name="status" class="w-full border px-4 py-2 rounded-lg">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>{{ __('admin/orders.status.pending') }}</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>{{ __('admin/orders.status.completed') }}</option>
                    <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>{{ __('admin/orders.status.canceled') }}</option>
                    <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>{{ __('admin/orders.status.refunded') }}</option>
                </select>
            </div>
        </div>

        <!-- Shipping Condition -->
        <div class="mt-6 bg-gray-100 p-4 rounded-lg">
            <h3 class="text-lg font-semibold">
                <i class="bi bi-truck"></i> {{ __('admin/orders.shipping_condition') }}
            </h3>
            <select name="shipping_condition_id" class="w-full border px-4 py-2 rounded-lg">
                @foreach ($shippingConditions as $condition)
                    <option value="{{ $condition->id }}" {{ $order->shipping_condition_id == $condition->id ? 'selected' : '' }}>
                        {{ $condition->name }} - ${{ number_format($condition->fee, 2) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Addresses -->
        <div class="grid grid-cols-2 gap-6 mt-6">
            <!-- Billing Address -->
            <div class="bg-gray-100 p-4 rounded-lg">
                <h3 class="text-lg font-semibold"><i class="bi bi-receipt"></i> {{ __('admin/orders.billing_address') }}</h3>
                @if ($billingAddress)
                    <input type="text" name="billing_address" value="{{ old('billing_address', $billingAddress->address) }}" class="w-full border px-4 py-2 rounded-lg mt-2">
                    <input type="text" name="billing_city" value="{{ old('billing_city', $billingAddress->city) }}" class="w-full border px-4 py-2 rounded-lg mt-2">
                    <input type="text" name="billing_state" value="{{ old('billing_state', $billingAddress->state) }}" class="w-full border px-4 py-2 rounded-lg mt-2">
                    <input type="text" name="billing_country" value="{{ old('billing_country', $billingAddress->country) }}" class="w-full border px-4 py-2 rounded-lg mt-2">
                    <input type="text" name="billing_zipcode" value="{{ old('billing_zipcode', $billingAddress->zipcode) }}" class="w-full border px-4 py-2 rounded-lg mt-2">
                @else
                    <p class="text-gray-500 italic">{{ __('admin/orders.no_billing_address') }}</p>
                @endif
            </div>

            <!-- Shipping Addresses -->
            <div class="bg-gray-100 p-4 rounded-lg">
                <h3 class="text-lg font-semibold"><i class="bi bi-geo-alt"></i> {{ __('admin/orders.shipping_address') }}</h3>
                @if ($shippingAddresses->count() > 0)
                    @foreach ($shippingAddresses as $index => $address)
                        <input type="text" name="shipping_addresses[{{ $index }}][address]" value="{{ old("shipping_addresses.$index.address", $address->address) }}" class="w-full border px-4 py-2 rounded-lg mt-2">
                        <input type="text" name="shipping_addresses[{{ $index }}][city]" value="{{ old("shipping_addresses.$index.city", $address->city) }}" class="w-full border px-4 py-2 rounded-lg mt-2">
                        <input type="text" name="shipping_addresses[{{ $index }}][state]" value="{{ old("shipping_addresses.$index.state", $address->state) }}" class="w-full border px-4 py-2 rounded-lg mt-2">
                        <input type="text" name="shipping_addresses[{{ $index }}][country]" value="{{ old("shipping_addresses.$index.country", $address->country) }}" class="w-full border px-4 py-2 rounded-lg mt-2">
                        <input type="text" name="shipping_addresses[{{ $index }}][zipcode]" value="{{ old("shipping_addresses.$index.zipcode", $address->zipcode) }}" class="w-full border px-4 py-2 rounded-lg mt-2">
                        <hr class="my-4">
                    @endforeach
                @else
                    <p class="text-gray-500 italic">{{ __('admin/orders.no_shipping_address') }}</p>
                @endif
            </div>
        </div>

        <!-- Order Total -->
        <div class="mt-4 text-right text-lg font-semibold">
            {{ __('admin/orders.total') }}: <span class="text-green-700">${{ number_format($order->total, 2) }}</span>
        </div>

        <!-- Order Items -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2"><i class="bi bi-bag"></i> {{ __('admin/orders.order_items') }}</h3>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300 text-sm">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 border">{{ __('admin/orders.artwork') }}</th>
                            <th class="px-4 py-2 border">{{ __('admin/orders.quantity') }}</th>
                            <th class="px-4 py-2 border">{{ __('admin/orders.unit_price') }}</th>
                            <th class="px-4 py-2 border">{{ __('admin/orders.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                        <tr class="border">
                            <td class="px-4 py-2 border">
                                <div class="flex items-center space-x-2">
                                    <img src="{{ asset('storage/' . $item->artwork->image) }}" class="w-12 h-12 object-cover rounded-lg border">
                                    <span>{{ $item->artwork->name }}</span>
                                </div>
                                <input type="hidden" name="artworks[]" value="{{ $item->artwork->id }}">
                            </td>
                            <td class="px-4 py-2 border text-center">
                                <input type="number" name="quantities[{{ $item->artwork->id }}]" value="{{ $item->quantity }}" class="border px-2 py-1 w-16" min="1">
                            </td>
                            <td class="px-4 py-2 border text-center">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-4 py-2 border text-center">
                                <button type="button" class="text-red-500 hover:underline" onclick="removeArtwork({{ $item->artwork->id }})">
                                    {{ __('admin/orders.remove') }}
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Submit -->
        <div class="mt-6 flex justify-between">
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                <i class="bi bi-arrow-left"></i> {{ __('admin/orders.cancel') }}
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                <i class="bi bi-save"></i> {{ __('admin/orders.update_order') }}
            </button>
        </div>
    </form>
</div>
@endsection
