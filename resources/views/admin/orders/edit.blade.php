@extends('layouts.admin')

@section('title', __('admin/orders.edit_order'))
@section('page-title', __('admin/orders.edit_order'))

@section('content')
<div class="container mx-auto p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-semibold mb-4">{{ __('admin/orders.edit_order') }} #{{ $order->id }}</h2>

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
                            {{ $user->name }} ({{ $user->email }})
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
            <h3 class="text-lg font-semibold">{{ __('admin/orders.shipping_condition') }}</h3>
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
                <h3 class="text-lg font-semibold">{{ __('admin/orders.billing_address') }}</h3>
                <input type="text" name="billing_address" value="{{ $order->billingAddress->address ?? '' }}" class="w-full border px-4 py-2 rounded-lg mt-2">
                <input type="text" name="billing_city" value="{{ $order->billingAddress->city ?? '' }}" class="w-full border px-4 py-2 rounded-lg mt-2">
                <input type="text" name="billing_state" value="{{ $order->billingAddress->state ?? '' }}" class="w-full border px-4 py-2 rounded-lg mt-2">
                <input type="text" name="billing_country" value="{{ $order->billingAddress->country ?? '' }}" class="w-full border px-4 py-2 rounded-lg mt-2">
                <input type="text" name="billing_zipcode" value="{{ $order->billingAddress->zipcode ?? '' }}" class="w-full border px-4 py-2 rounded-lg mt-2">
            </div>

            <!-- Shipping Address -->
            <div class="bg-gray-100 p-4 rounded-lg">
                <h3 class="text-lg font-semibold">{{ __('admin/orders.shipping_address') }}</h3>
                <input type="text" name="shipping_address" value="{{ $order->shippingAddress->address ?? '' }}" class="w-full border px-4 py-2 rounded-lg mt-2">
                <input type="text" name="shipping_city" value="{{ $order->shippingAddress->city ?? '' }}" class="w-full border px-4 py-2 rounded-lg mt-2">
                <input type="text" name="shipping_state" value="{{ $order->shippingAddress->state ?? '' }}" class="w-full border px-4 py-2 rounded-lg mt-2">
                <input type="text" name="shipping_country" value="{{ $order->shippingAddress->country ?? '' }}" class="w-full border px-4 py-2 rounded-lg mt-2">
                <input type="text" name="shipping_zipcode" value="{{ $order->shippingAddress->zipcode ?? '' }}" class="w-full border px-4 py-2 rounded-lg mt-2">
            </div>
        </div>

        <!-- Order Items -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">{{ __('admin/orders.order_items') }}</h3>
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
                                    <input type="number" name="quantities[{{ $item->artwork->id }}]" value="{{ $item->quantity }}" class="border px-2 py-1 w-16">
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
                {{ __('admin/orders.cancel') }}
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                {{ __('admin/orders.update_order') }}
            </button>
        </div>
    </form>
</div>
@endsection
