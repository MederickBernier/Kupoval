@extends('layouts.admin')

@section('title', 'Create Artwork')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-700">Add New Artwork</h2>

    <form action="{{ route('admin.artworks.store') }}" method="POST" enctype="multipart/form-data" x-data="{ isForEvent: '0' }">
        @csrf

        <!-- 🖼️ Informations générales -->
        <div class="mb-6 border-b pb-4">
            <h3 class="text-lg font-semibold text-gray-600 mb-4">Artwork Details</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Name:</label>
                    <input type="text" name="name" class="w-full border border-gray-300 px-4 py-2 rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Artist:</label>
                    <select name="artist_id" class="w-full border border-gray-300 px-4 py-2 rounded-lg" required>
                        <option value="">-- Select an Artist --</option>
                        @foreach($artists as $artist)
                            <option value="{{ $artist->id }}">{{ $artist->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description:</label>
                <textarea name="description" class="w-full border border-gray-300 px-4 py-2 rounded-lg h-24"></textarea>
            </div>
        </div>

        <!-- 📏 Dimensions & Prix -->
        <div class="mb-6 border-b pb-4">
            <h3 class="text-lg font-semibold text-gray-600 mb-4">Size & Pricing</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Price ($):</label>
                    <input type="number" name="initial_price" class="w-full border border-gray-300 px-4 py-2 rounded-lg" step="0.01" min="0">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Height (cm):</label>
                    <input type="number" name="height" class="w-full border border-gray-300 px-4 py-2 rounded-lg" step="0.01" min="0">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Width (cm):</label>
                    <input type="number" name="width" class="w-full border border-gray-300 px-4 py-2 rounded-lg" step="0.01" min="0">
                </div>
            </div>
        </div>

        <!-- 🎨 Image Upload -->
        <div class="mb-6 border-b pb-4">
            <h3 class="text-lg font-semibold text-gray-600 mb-4">Upload Artwork Image</h3>
            <input type="file" name="image" class="w-full border border-gray-300 px-4 py-2 rounded-lg" required>
        </div>

        <!-- 🏷️ Statut de mise en vente & événement -->
        <div class="mb-6 p-4 border border-gray-300 rounded-lg bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-600 mb-4">Sale & Event Options</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">For Sale:</label>
                    <select name="is_on_sale" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                        <option value="1">Yes</option>
                        <option value="0" selected>No</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Featured:</label>
                    <select name="is_featured" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                        <option value="1">Yes</option>
                        <option value="0" selected>No</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">For an Event?</label>
                <select name="is_for_event" x-model="isForEvent" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                    <option value="0" selected>No</option>
                    <option value="1">Yes</option>
                </select>
            </div>

            <div class="mt-4" x-show="isForEvent === '1'" x-transition>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Select Event:</label>
                <select name="event_id" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                    <option value="">-- Select an Event --</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Boutons -->
        <div class="flex justify-end space-x-2">
            <a href="{{ route('admin.artworks.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                Save Artwork
            </button>
        </div>
    </form>
</div>
@endsection
