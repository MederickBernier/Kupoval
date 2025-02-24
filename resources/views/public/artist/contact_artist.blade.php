@extends('layouts.public')

@section('title', __('public/contact.contact_artist_title', ['artist' => $artist->name]))

@section('content')
<div class="container mx-auto max-w-4xl p-6">
    <h1 class="text-3xl font-semibold text-heading text-center mb-6">
        {{ __('public/contact.contact_artist_title', ['artist' => $artist->name]) }}
    </h1>

    <p class="text-center text-gray-600 mb-8">
        {{ __('public/contact.fill_form_message', ['artist' => $artist->name]) }}
    </p>

    <div class="bg-white shadow-lg rounded-lg p-6">
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('contact.artist.send') }}" method="POST">
            @csrf
            <input type="hidden" name="artist_id" value="{{ $artist->id }}">

            <!-- Artist's Email (Disabled) -->
            <div class="mb-4">
                <label for="artist_email" class="block font-semibold text-gray-700">{{ __('public/contact.artist_email') }}</label>
                <input type="email" id="artist_email" name="artist_email"
                       class="w-full p-3 border rounded-lg bg-gray-100 text-gray-600 mt-1"
                       value="{{ $artist->email }}" disabled>
            </div>

            <!-- Sender's Name -->
            <div class="mb-4">
                <label for="name" class="block font-semibold text-gray-700">{{ __('public/contact.name') }}</label>
                <input type="text" id="name" name="name"
                       class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                       value="{{ old('name', auth()->user()->name ?? '') }}" required>
                @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Sender's Email (Disabled but sent) -->
            <div class="mb-4">
                <label for="email" class="block font-semibold text-gray-700">{{ __('public/contact.your_email') }}</label>
                <input type="email" id="email" name="email"
                       class="w-full p-3 border rounded-lg bg-gray-100 text-gray-600 mt-1"
                       value="{{ old('email', auth()->user()->email ?? '') }}" required readonly>
                @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Subject -->
            <div class="mb-4">
                <label for="subject" class="block font-semibold text-gray-700">{{ __('public/contact.subject') }}</label>
                <input type="text" id="subject" name="subject"
                       class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                       value="{{ old('subject') }}">
                @error('subject') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Message -->
            <div class="mb-4">
                <label for="message" class="block font-semibold text-gray-700">{{ __('public/contact.message') }}</label>
                <textarea id="message" name="message"
                          class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1 h-32"
                          required>{{ old('message') }}</textarea>
                @error('message') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Submit Button -->
            <div class="text-right">
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 transition">
                    {{ __('public/contact.send') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
