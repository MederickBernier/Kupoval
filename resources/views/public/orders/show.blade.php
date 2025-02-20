@extends('layouts.public')

@section('title', __('public/order.details_title', ['order' => $order->id]))

@section('content')
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-3xl font-semibold mb-6 text-heading border-b pb-2">
        {{ __('public/order.details_title', ['order' => $order->id]) }}
    </h2>

    <!-- Order Info -->
    <div class="mb-6">
        <p><strong>{{ __('public/order.date') }}:</strong> {{ $order->created_at->format('d M Y') }}</p>
        <p><strong>{{ __('public/order.status') }}:</strong>
            <span class="px-2 py-1 text-sm rounded {{ $order->status === 'completed' ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-800' }}">
                {{ ucfirst($order->status) }}
            </span>
        </p>
        <p><strong>{{ __('public/order.total') }}:</strong> ${{ number_format($order->total, 2) }}</p>
    </div>

    <!-- Items -->
    <h3 class="text-xl font-semibold mt-6 mb-4">{{ __('public/order.items') }}</h3>
    <table class="w-full border rounded-lg">
        <thead class="bg-gray-200 text-gray-800">
            <tr>
                <th class="px-4 py-2 text-left">{{ __('public/order.item') }}</th>
                <th class="px-4 py-2 text-center">{{ __('public/order.quantity') }}</th>
                <th class="px-4 py-2 text-right">{{ __('public/order.unit_price') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr class="border-t hover:bg-gray-100">
                <td class="px-4 py-2">{{ $item->artwork->name }}</td>
                <td class="px-4 py-2 text-center">{{ $item->quantity }}</td>
                <td class="px-4 py-2 text-right">${{ number_format($item->unit_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Download Invoice Button -->
    <div class="mt-6">
        <a href="{{ route('orders.invoice', $order->id) }}"
           class="inline-block px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
            {{ __('public/order.download_invoice') }}
        </a>
    </div>
</div>
@endsection
