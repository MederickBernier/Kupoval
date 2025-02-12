@extends('layouts.admin')

@section('title', __('admin/orders.order_details'))
@section('page-title', __('admin/orders.order_details'))

@section('content')
<div class="container mx-auto p-6 bg-white shadow-md rounded-lg">
    <!-- Order Header -->
    <div class="flex justify-between items-center border-b pb-4 mb-4">
        <h2 class="text-2xl font-semibold">{{ __('admin/orders.order_id') }} #{{ $order->id }}</h2>
        <span class="px-3 py-1 rounded-full text-sm font-semibold
            {{ $order->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : '' }}
            {{ $order->status === 'completed' ? 'bg-green-200 text-green-800' : '' }}
            {{ $order->status === 'canceled' ? 'bg-red-200 text-red-800' : '' }}">
            {{ __('admin/orders.status.' . $order->status) }}
        </span>
    </div>

    <!-- Order Info -->
    <div class="grid grid-cols-2 gap-6">
        <!-- Customer Details -->
        <div class="bg-gray-100 p-4 rounded-lg">
            <h3 class="text-lg font-semibold">{{ __('admin/orders.customer') }}</h3>
            <p class="text-gray-700"><strong>{{ $order->user->name ?? __('admin/orders.anonymous') }}</strong></p>
            <p class="text-gray-700">{{ $order->user->email ?? __('N/A') }}</p>
            <p class="text-gray-700">{{ $order->recipient_phone ?? __('N/A') }}</p>
        </div>

        <!-- Order Details -->
        <div class="bg-gray-100 p-4 rounded-lg">
            <h3 class="text-lg font-semibold">{{ __('admin/orders.order_summary') }}</h3>
            <p class="text-gray-700"><strong>{{ __('admin/orders.total_price') }}:</strong> ${{ number_format($order->total, 2) }}</p>
            <p class="text-gray-700"><strong>{{ __('admin/orders.date') }}:</strong> {{ $order->created_at->format('Y-m-d') }}</p>
            <p class="text-gray-700"><strong>{{ __('admin/orders.shipping_condition') }}:</strong> {{ $order->shippingCondition->name ?? __('N/A') }}</p>
        </div>
    </div>

    <!-- Shipping Condition Details -->
    @if($shippingConditions->count() > 0)
        <div class="mt-6 bg-gray-100 p-4 rounded-lg">
            <h3 class="text-lg font-semibold">{{ __('admin/orders.available_shipping_conditions') }}</h3>
            <ul class="text-gray-700 list-disc list-inside">
                @foreach ($shippingConditions as $condition)
                    <li>
                        <strong>{{ $condition->name }}</strong>
                        ({{ $condition->description }}) -
                        ${{ number_format($condition->fee, 2) }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Addresses -->
    <div class="grid grid-cols-2 gap-6 mt-6">
        <!-- Billing Address -->
        <div class="bg-gray-100 p-4 rounded-lg">
            <h3 class="text-lg font-semibold">{{ __('admin/orders.billing_address') }}</h3>
            <p class="text-gray-700">{{ $order->billingAddress->address ?? __('N/A') }}</p>
            <p class="text-gray-700">{{ $order->billingAddress->city ?? __('N/A') }}, {{ $order->billingAddress->state ?? __('N/A') }}</p>
            <p class="text-gray-700">{{ $order->billingAddress->country ?? __('N/A') }}, {{ $order->billingAddress->zipcode ?? __('N/A') }}</p>
        </div>

        <!-- Shipping Address -->
        <div class="bg-gray-100 p-4 rounded-lg">
            <h3 class="text-lg font-semibold">{{ __('admin/orders.shipping_address') }}</h3>
            @if ($order->shippingAddress)
                <p class="text-gray-700">{{ $order->shippingAddress->address }}</p>
                <p class="text-gray-700">{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}</p>
                <p class="text-gray-700">{{ $order->shippingAddress->country }}, {{ $order->shippingAddress->zipcode }}</p>
            @else
                <p class="text-gray-500 italic">{{ __('admin/orders.same_as_billing') }}</p>
            @endif
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
                        <th class="px-4 py-2 border">{{ __('admin/orders.total') }}</th>
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
                            </td>
                            <td class="px-4 py-2 border text-center">{{ $item->quantity }}</td>
                            <td class="px-4 py-2 border text-center">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-4 py-2 border text-center">${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-6 flex justify-between">
        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
            <i class="bi bi-arrow-left"></i> {{ __('admin/orders.back') }}
        </a>
        <div class="space-x-2">
            <a href="{{ route('admin.orders.edit', $order->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                <i class="bi bi-pencil"></i> {{ __('admin/orders.edit') }}
            </a>
            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="inline-block">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600" onclick="return confirm('{{ __('admin/orders.delete_confirmation') }}')">
                    <i class="bi bi-trash"></i> {{ __('admin/orders.delete') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
