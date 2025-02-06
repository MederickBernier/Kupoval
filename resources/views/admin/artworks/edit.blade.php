@extends('layouts.admin')

@section('title', 'Edit Artwork')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-gray-700">Edit Artwork</h2>

    <!-- Affichage des erreurs -->
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.artworks.update', $artwork->id) }}" method="POST" enctype="multipart/form-data" x-data="{ isForEvent: '{{ old('is_for_event', $artwork->is_for_event) }}' }">
        @csrf
        @method('PUT')

        <!-- 🖼️ Informations générales -->
        <div class="mb-6 border-b pb-4">
            <h3 class="text-lg font-semibold text-gray-600 mb-4">Artwork Details</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Name:</label>
                    <input type="text" name="name" value="{{ old('name', $artwork->name) }}"
                           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring focus:ring-blue-200" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Artist:</label>
                    <select name="artist_id" class="w-full border border-gray-300 px-4 py-2 rounded-lg" required>
                        <option value="">-- Select an Artist --</option>
                        @foreach($artists as $artist)
                            <option value="{{ $artist->id }}" {{ old('artist_id', $artwork->artist_id) == $artist->id ? 'selected' : '' }}>
                                {{ $artist->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description:</label>
                <textarea name="description" class="w-full border border-gray-300 px-4 py-2 rounded-lg h-24 focus:ring focus:ring-blue-200">
                    {{ old('description', $artwork->description) }}
                </textarea>
            </div>
        </div>

        <!-- 📏 Dimensions & Prix -->
        <div class="mb-6 border-b pb-4">
            <h3 class="text-lg font-semibold text-gray-600 mb-4">Size & Pricing</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Price ($):</label>
                    <input type="number" name="initial_price" value="{{ old('initial_price', $artwork->initial_price) }}"
                           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring focus:ring-blue-200" step="0.01" min="0">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Height (cm):</label>
                    <input type="number" name="height" value="{{ old('height', $artwork->height) }}"
                           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring focus:ring-blue-200" step="0.01" min="0">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Width (cm):</label>
                    <input type="number" name="width" value="{{ old('width', $artwork->width) }}"
                           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring focus:ring-blue-200" step="0.01" min="0">
                </div>
            </div>
        </div>

        <!-- 🎨 Image Upload -->
        <div class="mb-6 border-b pb-4">
            <h3 class="text-lg font-semibold text-gray-600 mb-4">Artwork Image</h3>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Current Image:</label>
                <img src="{{ asset('storage/artworks/' . basename($artwork->image)) }}"
                     alt="Artwork Image" class="w-32 h-32 object-cover rounded-lg shadow">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Upload New Image (optional):</label>
                <input type="file" name="image" class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring focus:ring-blue-200">
            </div>
        </div>

        <!-- 🏷️ Statut de mise en vente & événement -->
        <div class="mb-6 p-4 border border-gray-300 rounded-lg bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-600 mb-4">Sale & Event Options</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">For Sale:</label>
                    <select name="is_on_sale" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                        <option value="1" {{ old('is_on_sale', $artwork->is_on_sale) ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ !old('is_on_sale', $artwork->is_on_sale) ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Featured:</label>
                    <select name="is_featured" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                        <option value="1" {{ old('is_featured', $artwork->is_featured) ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ !old('is_featured', $artwork->is_featured) ? 'selected' : '' }}>No</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">For an Event?</label>
                <select name="is_for_event" x-model="isForEvent" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                    <option value="0" {{ old('is_for_event', $artwork->is_for_event) ? '' : 'selected' }}>No</option>
                    <option value="1" {{ old('is_for_event', $artwork->is_for_event) ? 'selected' : '' }}>Yes</option>
                </select>
            </div>

            <div class="mt-4" x-show="isForEvent === '1'" x-transition>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Select Event:</label>
                <select name="event_id" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                    <option value="">-- Select an Event --</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ old('event_id', $artwork->event_id) == $event->id ? 'selected' : '' }}>
                            {{ $event->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Boutons -->
        <div class="flex justify-between mt-6">
            <a href="{{ route('admin.artworks.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Return to Artworks
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
