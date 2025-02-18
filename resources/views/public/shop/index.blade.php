@extends('layouts.public')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <h1 class="text-3xl font-semibold text-gray-800">{{ __('public/shop.title') }}</h1>

        <!-- Info Warning -->
        {{-- <div class="bg-yellow-100 text-yellow-800 p-4 rounded-md shadow-md mb-6 flex items-center">
            <i class="bi bi-exclamation-triangle-fill mr-3 text-2xl"></i>
            <p>{{ __('public/shop.shop_info_warning') }}</p>
        </div> --}}

        <!-- Shop Component -->
        @livewire('shop-component')
    </div>
@endsection
