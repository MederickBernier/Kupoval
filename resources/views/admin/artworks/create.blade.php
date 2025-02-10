@extends('layouts.admin')

@section('title', __('admin/artworks.create_title'))

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-700">{{ __('admin/artworks.add_new') }}</h2>

    <form action="{{ route('admin.artworks.store') }}" method="POST" enctype="multipart/form-data" x-data="{ isForEvent: '0', selectedCategories: [] }">
        @csrf

        <!-- 🖼️ Informations générales -->
        <div class="mb-6 border-b pb-4">
            <h3 class="text-lg font-semibold text-gray-600 mb-4">{{ __('admin/artworks.details') }}</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.name') }}:</label>
                    <input type="text" name="name" class="w-full border border-gray-300 px-4 py-2 rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.artist') }}:</label>
                    <select name="artist_id" class="w-full border border-gray-300 px-4 py-2 rounded-lg" required>
                        <option value="">{{ __('admin/artworks.select_artist') }}</option>
                        @foreach($artists as $artist)
                            <option value="{{ $artist->id }}">{{ $artist->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.description') }}:</label>
                <textarea name="description" class="editor w-full border border-gray-300 px-4 py-2 rounded-lg h-24"></textarea>
            </div>
        </div>

        <!-- 🏷️ Sélection des catégories -->
        <div class="mb-6 border-b pb-4">
            <h3 class="text-lg font-semibold text-gray-600 mb-4">{{ __('admin/artworks.categories') }}</h3>

            <!-- Input caché pour envoyer les catégories sélectionnées -->
            <input type="hidden" name="categories" x-bind:value="selectedCategories.join(',')">

            <div class="flex flex-wrap gap-2">
                @foreach($categories as $category)
                    <button
                        type="button"
                        @click="selectedCategories.includes({{ $category->id }})
                            ? selectedCategories.splice(selectedCategories.indexOf({{ $category->id }}), 1)
                            : selectedCategories.push({{ $category->id }})"
                        x-bind:class="selectedCategories.includes({{ $category->id }})
                            ? 'bg-blue-500 text-white'
                            : 'bg-gray-200 text-gray-700'"
                        class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-300 transition">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- 📏 Dimensions & Prix -->
        <div class="mb-6 border-b pb-4">
            <h3 class="text-lg font-semibold text-gray-600 mb-4">{{ __('admin/artworks.size_pricing') }}</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.price') }} ($):</label>
                    <input type="number" name="initial_price" class="w-full border border-gray-300 px-4 py-2 rounded-lg" step="0.01" min="0">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.height') }} (cm):</label>
                    <input type="number" name="height" class="w-full border border-gray-300 px-4 py-2 rounded-lg" step="0.01" min="0">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.width') }} (cm):</label>
                    <input type="number" name="width" class="w-full border border-gray-300 px-4 py-2 rounded-lg" step="0.01" min="0">
                </div>
            </div>
        </div>

        <!-- 🎨 Image Upload -->
        <div class="mb-6 border-b pb-4">
            <h3 class="text-lg font-semibold text-gray-600 mb-4">{{ __('admin/artworks.upload_image') }}</h3>
            <input type="file" name="image" class="w-full border border-gray-300 px-4 py-2 rounded-lg" required>
        </div>

        <!-- 🏷️ Statut de mise en vente & événement -->
        <div class="mb-6 p-4 border border-gray-300 rounded-lg bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-600 mb-4">{{ __('admin/artworks.sale_event_options') }}</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.for_sale') }}:</label>
                    <select name="is_on_sale" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                        <option value="1">{{ __('admin/artworks.yes') }}</option>
                        <option value="0" selected>{{ __('admin/artworks.no') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.featured') }}:</label>
                    <select name="is_featured" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                        <option value="1">{{ __('admin/artworks.yes') }}</option>
                        <option value="0" selected>{{ __('admin/artworks.no') }}</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.for_event') }}:</label>
                <select name="is_for_event" x-model="isForEvent" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                    <option value="0" selected>{{ __('admin/artworks.no') }}</option>
                    <option value="1">{{ __('admin/artworks.yes') }}</option>
                </select>
            </div>

            <div class="mt-4" x-show="isForEvent === '1'" x-transition>
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.select_event') }}:</label>
                <select name="event_id" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                    <option value="">{{ __('admin/artworks.select_event_placeholder') }}</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Boutons -->
        <div class="flex justify-end space-x-2">
            <a href="{{ route('admin.artworks.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                {{ __('admin/artworks.cancel') }}
            </a>
            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                {{ __('admin/artworks.save') }}
            </button>
        </div>
    </form>
</div>
@endsection
