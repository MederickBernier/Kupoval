@extends('layouts.public')

@section('content')
<section class="my-12">
    <div class="container mx-auto max-w-md">
        <h1 class="text-3xl font-title text-heading text-center mb-6">{{ __('Register') }}</h1>

        <form action="{{ route('register') }}" method="POST" class="bg-white p-6 rounded-lg shadow-lg">
            @csrf
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">{{ __('Username') }}</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-accent focus:border-accent">
                @error('username') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-accent focus:border-accent">
                @error('email') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
                <input type="password" name="password" id="password" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-accent focus:border-accent">
                @error('password') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('Confirm Password') }}</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-accent focus:border-accent">
            </div>

            <div>
                <button type="submit" class="w-full px-4 py-2 bg-accent text-white font-bold rounded-lg hover:bg-cta transition">
                    {{ __('Register') }}
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
