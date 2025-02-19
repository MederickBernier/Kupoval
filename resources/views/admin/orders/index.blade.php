@extends('layouts.admin')

@section('title', __('admin/orders.list_title'))
@section('page-title', __('admin/orders.list_title'))

@section('content')

<div x-data="orderManager()" class="bg-white p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">{{ __('admin/orders.list_heading') }}</h2>
        <a href="{{ route('admin.orders.create') }}"
        class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
            <i class="bi bi-plus-lg"></i> {{ __('admin/orders.create_order') }}
        </a>
    </div>

    {{-- <!-- Search & Filters -->
    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex space-x-2 mb-4">
        <input type="text" name="search" value="{{ request('search') }}"
            class="border border-gray-300 px-4 py-2 rounded-lg w-60 focus:ring focus:ring-blue-300"
            placeholder="{{ __('admin/orders.search_placeholder') }}">

        <select name="status"
                class="border border-gray-300 px-4 py-2 rounded-lg w-48 focus:ring focus:ring-blue-300"
                onchange="this.form.submit()">
            <option value="">{{ __('admin/orders.filter_status') }}</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                {{ __('admin/orders.status.pending') }}
            </option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                {{ __('admin/orders.status.completed') }}
            </option>
            <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>
                {{ __('admin/orders.status.canceled') }}
            </option>
            <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>
                {{ __('admin/orders.status.refunded') }}
            </option>
        </select>

        <button type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            {{ __('admin/orders.filter') }}
        </button>

        @if(request()->has('search') || request()->has('status'))
            <a href="{{ route('admin.orders.index') }}"
            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                {{ __('admin/orders.clear_filters') }}
            </a>
        @endif
    </form> --}}

    <!-- Orders Table -->
    @if($orders->isEmpty())
        <div class="bg-white shadow-lg rounded-lg mt-6 p-6 text-center">
            <p class="text-gray-600 text-lg">
                {{ __('admin/orders.no_orders') }}
            </p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300 text-sm sm:text-base">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 border">{{ __('admin/orders.order_id') }}</th>
                        <th class="px-4 py-2 border">{{ __('admin/orders.customer') }}</th>
                        <th class="px-4 py-2 border">{{ __('admin/orders.total_price') }}</th>
                        <th class="px-4 py-2 border">{{ __('admin/orders.status') }}</th>
                        <th class="px-4 py-2 border">{{ __('admin/orders.date') }}</th>
                        <th class="px-4 py-2 border">{{ __('admin/orders.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="border">
                            <td class="px-4 py-2 border">{{ $order->id }}</td>
                            <td class="px-4 py-2 border">
                                @if ($order->user && $order->user->profile)
                                    {{ $order->user->profile->first_name ?? '' }} {{ $order->user->profile->last_name ?? '' }}
                                    ({{ $order->user->email ?? __('admin/orders.anonymous') }})
                                @else
                                    {{ __('admin/orders.anonymous') }}
                                @endif
                            </td>
                            <td class="px-4 py-2 border font-semibold">${{ number_format($order->total, 2) }}</td>
                            <td class="px-4 py-2 border text-center">
                                <span class="px-2 py-1 text-sm font-semibold rounded-full
                                    {{ $order->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : '' }}
                                    {{ $order->status === 'completed' ? 'bg-green-200 text-green-800' : '' }}
                                    {{ $order->status === 'canceled' ? 'bg-red-200 text-red-800' : '' }}
                                    {{ $order->status === 'refunded' ? 'bg-gray-300 text-gray-800' : '' }}">
                                    {{ __('admin/orders.status.' . (is_string($order->status) ? $order->status : 'unknown')) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 border text-gray-700">{{ $order->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-2 border text-center space-x-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                   class="text-blue-500 hover:underline">
                                    <i class="bi bi-eye"></i> {{ __('admin/orders.view') }}
                                </a>
                                <a href="{{ route('admin.orders.edit', $order->id) }}"
                                   class="text-yellow-500 hover:underline">
                                    <i class="bi bi-pencil"></i> {{ __('admin/orders.edit') }}
                                </a>
                                <button @click="setDeleteOrder({{ json_encode($order) }}, '{{ route('admin.orders.destroy', $order->id) }}')"
                                        class="text-red-500 hover:underline">
                                    <i class="bi bi-trash"></i> {{ __('admin/orders.deactivate') }}
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @endif

    @include('blade_components.modals.admin.orders.delete')
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('orderManager', () => ({
        openDeleteOrderModal: false,
        selectedOrder: { id: '', total_price: '', status: '' },
        deleteUrl: '',
        deleteConfirmation: '',

        setDeleteOrder(order, url) {
            this.selectedOrder = order;
            this.deleteUrl = url;
            this.deleteConfirmation = '';
            this.openDeleteOrderModal = true;
        }
    }));
});
</script>

@endsection
