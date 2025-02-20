@extends('layouts.public')

@section('title', __('public/profile.title'))

@section('content')
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-3xl font-semibold mb-6 text-heading border-b border-accent pb-2 flex items-center">
        <i class="bi bi-person-circle text-heading text-xl mr-2"></i> {{ __('public/profile.title') }}
    </h2>

    <div class="shadow-md p-6 rounded-lg bg-white mb-6">
        <h3 class="text-xl font-semibold mb-4 text-accent flex items-center border-b pb-2">
            <i class="bi bi-person text-accent text-lg mr-2"></i> {{ __('public/profile.personal_info') }}
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
        </div>
    </div>

    <div class="shadow-md p-6 rounded-lg bg-white mb-6">
        <h3 class="text-xl font-semibold mb-4 text-accent flex items-center border-b pb-2">
            <i class="bi bi-truck text-accent text-lg mr-2"></i> {{ __('public/profile.shipping_addresses') }}
        </h3>

        @if(Auth::user()->profile->shippingAddresses->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach(Auth::user()->profile->shippingAddresses as $address)
                    <livewire:update-address :addressId="$address->id" type="shipping" />
                @endforeach
            </div>
        @else
            <p class="text-gray-500 bg-gray-100 p-3 rounded">{{ __('public/profile.no_shipping_addresses') }}</p>
        @endif
    </div>
</div>
@endsection
