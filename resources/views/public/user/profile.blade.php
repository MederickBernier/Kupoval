@extends('layouts.public')

@section('title', __('public/profile.title'))

@section('content')
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-3xl font-semibold mb-6 text-heading border-b border-accent pb-2 flex items-center">
        <i class="bi bi-person-circle text-heading text-xl mr-2"></i> {{ __('public/profile.title') }}
    </h2>

    <!-- Informations Personnelles -->
    <div class="shadow-md p-6 rounded-lg bg-white mb-6">
        <h3 class="text-xl font-semibold mb-4 text-accent flex items-center">
            <i class="bi bi-person text-accent text-lg mr-2"></i> {{ __('public/profile.personal_info') }}
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach(['first_name', 'last_name', 'email', 'username'] as $field)
                <div class="flex flex-col md:flex-row md:items-center gap-y-2">
                    <label class="font-semibold text-gray-800 md:w-1/3 text-left md:text-right pr-4">
                        {{ __('public/profile.' . $field) }}:
                    </label>
                    <div class="md:w-2/3 w-full">
                        <livewire:update-field field="{{ $field }}" :value="Auth::user()->profile->$field ?? Auth::user()->$field ?? ''" />
                    </div>
                </div>
            @endforeach
            <div class="flex flex-col md:flex-row md:items-center gap-y-2">
                <label class="font-semibold text-gray-800 md:w-1/3 text-left md:text-right pr-4">
                    {{ __('public/profile.password') }}:
                </label>
                <div class="md:w-2/3 w-full">
                    <livewire:update-password />
                </div>
            </div>
        </div>
    </div>

    <!-- Adresse de facturation -->
    <div class="shadow-md p-6 rounded-lg bg-white mb-6">
        <h3 class="text-xl font-semibold mb-4 text-accent flex items-center">
            <i class="bi bi-house text-accent text-lg mr-2"></i> {{ __('public/profile.billing_address') }}
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach(['address', 'city', 'zipcode', 'state', 'country', 'phone'] as $field)
                <div class="flex flex-col md:flex-row md:items-center gap-y-2">
                    <label class="font-semibold text-gray-800 md:w-1/3 text-left md:text-right pr-4">
                        {{ __('public/profile.' . $field) }}:
                    </label>
                    <div class="md:w-2/3 w-full">
                        <livewire:update-field field="{{ $field }}" :value="Auth::user()->profile->$field ?? ''" />
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Historique des commandes -->
    <div class="shadow-md p-6 rounded-lg bg-white mb-6">
        <h3 class="text-xl font-semibold mb-4 text-accent flex items-center">
            <i class="bi bi-box-seam text-accent text-lg mr-2"></i> {{ __('public/profile.order_history') }}
        </h3>
        @if(Auth::user()->orders->isEmpty())
            <p class="text-gray-500 bg-neutral p-3 rounded">{{ __('public/profile.no_orders') }}</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border rounded-lg">
                    <thead class="bg-gray-200">
                        <tr>
                            @foreach(['order_number', 'order_date', 'order_total', 'order_status', 'order_action'] as $column)
                                <th class="px-4 py-2 text-left">{{ __('public/profile.' . $column) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(Auth::user()->orders as $order)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $order->id }}</td>
                                <td class="px-4 py-2">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-2">{{ number_format($order->total, 2) }}$</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 text-sm rounded bg-gray-200 text-gray-800">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <a href="{{ route('order.details', $order->id) }}" class="text-link hover:underline">
                                        {{ __('public/profile.view_order') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Wishlist -->
    <div class="shadow-md p-6 rounded-lg bg-white mb-6">
        <h3 class="text-xl font-semibold mb-4 text-accent flex items-center">
            <i class="bi bi-heart text-accent text-lg mr-2"></i> {{ __('public/profile.wishlist') }}
        </h3>
        @if(Auth::user()->wishlist->isEmpty())
            <p class="text-gray-500 bg-neutral p-3 rounded">{{ __('public/profile.no_wishlist') }}</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach(Auth::user()->wishlist as $item)
                    <div class="border rounded-lg p-4 shadow-emerald flex flex-col items-center">
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-32 object-cover rounded">
                        <h4 class="text-lg font-semibold mt-2 text-heading">{{ $item->product->name }}</h4>
                        <p class="text-gray-700 mt-1">{{ number_format($item->product->price, 2) }}$</p>
                        <div class="flex justify-between w-full mt-3">
                            <a href="{{ route('product.details', $item->product->id) }}" class="text-link hover:underline">
                                {{ __('public/profile.view_product') }}
                            </a>
                            <button wire:click="removeFromWishlist({{ $item->id }})" class="text-red-500 hover:underline">
                                {{ __('public/profile.remove_product') }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
