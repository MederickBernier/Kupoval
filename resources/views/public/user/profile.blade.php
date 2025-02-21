@extends('layouts.public')

@section('title', __('public/profile.title'))

@section('content')
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-3xl font-semibold mb-6 text-heading border-b border-accent pb-2 flex items-center">
        <i class="bi bi-person-circle text-heading text-xl mr-2"></i> {{ __('public/profile.title') }}
    </h2>

    <!-- 📌 Informations Personnelles -->
    <div class="shadow-md p-6 rounded-lg bg-white mb-6">
        <h3 class="text-xl font-semibold mb-4 text-accent flex items-center border-b pb-2">
            <i class="bi bi-person text-accent text-lg mr-2"></i> {{ __('public/profile.personal_info') }}
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- 🔽 Add Title Dropdown -->
            <div class="flex flex-col">
                <livewire:update-title />
            </div>
            <div class="flex flex-col">
                <label class="font-semibold text-gray-800">{{ __('public/profile.first_name') }}:</label>
                <livewire:update-field field="first_name" :value="Auth::user()->profile->first_name ?? ''" />
            </div>
            <div class="flex flex-col">
                <label class="font-semibold text-gray-800">{{ __('public/profile.last_name') }}:</label>
                <livewire:update-field field="last_name" :value="Auth::user()->profile->last_name ?? ''" />
            </div>
            <div class="flex flex-col">
                <label class="font-semibold text-gray-800">{{ __('public/profile.email') }}:</label>
                <livewire:update-field field="email" :value="Auth::user()->email ?? ''" />
            </div>
            <div class="flex flex-col">
                <label class="font-semibold text-gray-800">{{ __('public/profile.username') }}:</label>
                <livewire:update-field field="username" :value="Auth::user()->username ?? ''" disabled />
            </div>
            <div class="flex flex-col">
                <label class="font-semibold text-gray-800">{{ __('public/profile.password') }}:</label>
                <livewire:update-password />
            </div>
        </div>
    </div>

    <!-- 🌍 Changer la Langue -->
    <div class="shadow-md p-6 rounded-lg bg-white mb-6">
        <h3 class="text-xl font-semibold mb-4 text-accent flex items-center border-b pb-2">
            <i class="bi bi-translate text-accent text-lg mr-2"></i> {{ __('public/profile.language_preference') }}
        </h3>
        <form action="{{ route('lang.switch') }}" method="POST" class="flex flex-col space-y-3">
            @csrf
            <label for="languageSwitcher" class="font-semibold text-gray-800">
                {{ __('public/profile.select_language') }}:
            </label>
            <select name="languageSwitcher" id="languageSwitcher"
                    class="border rounded-lg px-4 py-2 focus:ring focus:ring-accent focus:outline-none"
                    onchange="this.form.submit()">
                <option value="frca" {{ app()->getLocale() === 'frca' ? 'selected' : '' }}>🇨🇦 Français (Canada)</option>
                <option value="enca" {{ app()->getLocale() === 'enca' ? 'selected' : '' }}>🇨🇦 English (Canada)</option>
            </select>
        </form>
    </div>

    <!-- 🏡 Billing Address -->
    <div class="shadow-md p-6 rounded-lg bg-white mb-6">
        <h3 class="text-xl font-semibold mb-4 text-accent flex items-center border-b pb-2">
            <i class="bi bi-credit-card text-accent text-lg mr-2"></i> {{ __('public/profile.billing_address') }}
        </h3>

        @if(Auth::user()->profile->billingAddress)
            <livewire:update-address type="billing" :addressId="Auth::user()->profile->billingAddress->id" />
        @else
            <livewire:update-address type="billing" />
        @endif
    </div>

    <!-- 🚚 Shipping Addresses -->
    <div class="shadow-md p-6 rounded-lg bg-white mb-6">
        <h3 class="text-xl font-semibold mb-4 text-accent flex items-center border-b pb-2">
            <i class="bi bi-truck text-accent text-lg mr-2"></i> {{ __('public/profile.shipping_addresses') }}
        </h3>

        @foreach(Auth::user()->profile->shippingAddresses as $address)
            <livewire:update-address type="shipping" :addressId="$address->id" />
        @endforeach

        @if(Auth::user()->profile->shippingAddresses->isEmpty())
            <livewire:update-address type="shipping" />
        @endif
    </div>

    <!-- 📦 Historique des Commandes -->
    <div class="shadow-md p-6 rounded-lg bg-white mb-6">
        <h3 class="text-xl font-semibold mb-4 text-accent flex items-center border-b pb-2">
            <i class="bi bi-box-seam text-accent text-lg mr-2"></i> {{ __('public/profile.order_history') }}
        </h3>
        @if(Auth::user()->orders->isEmpty())
            <p class="text-gray-500 bg-gray-100 p-3 rounded">{{ __('public/profile.no_orders') }}</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border rounded-lg">
                    <thead class="bg-gray-200 text-gray-800">
                        <tr>
                            <th class="px-4 py-2 text-left">{{ __('public/profile.order_number') }}</th>
                            <th class="px-4 py-2 text-left">{{ __('public/profile.order_date') }}</th>
                            <th class="px-4 py-2 text-left">{{ __('public/profile.order_total') }}</th>
                            <th class="px-4 py-2 text-left">{{ __('public/profile.order_status') }}</th>
                            <th class="px-4 py-2">{{ __('public/profile.order_action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(Auth::user()->orders as $order)
                            <tr class="border-t hover:bg-gray-100">
                                <td class="px-4 py-2">{{ $order->id }}</td>
                                <td class="px-4 py-2">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-2 font-semibold">{{ number_format($order->total, 2) }}$</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 text-sm rounded bg-gray-200 text-gray-800">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <a href="{{ route('orders.show', $order->id) }}" class="text-link hover:underline">
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

    <!-- ❤️ Wishlist -->
    <div class="shadow-md p-6 rounded-lg bg-white mb-6">
        <h3 class="text-xl font-semibold mb-4 text-accent flex items-center border-b pb-2">
            <i class="bi bi-heart text-accent text-lg mr-2"></i> {{ __('public/profile.wishlist') }}
        </h3>

        @if(Auth::user()->wishlist->isEmpty())
            <p class="text-gray-500 bg-gray-100 p-3 rounded">{{ __('public/profile.no_wishlist') }}</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach(Auth::user()->wishlist as $item)
                    <div class="border rounded-lg p-4 shadow-sm flex flex-col items-center bg-gray-50 hover:bg-gray-100">
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-32 object-cover rounded">
                        <h4 class="text-lg font-semibold mt-2 text-heading">{{ $item->product->name }}</h4>
                        <p class="text-gray-700 mt-1">{{ number_format($item->product->price, 2) }}$</p>
                        <button class="mt-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                            {{ __('public/profile.remove_from_wishlist') }}
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
