@extends('layouts.admin')

@section('title', __('admin/artworks.edit_title'))

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-gray-700">{{ __('admin/artworks.edit_title') }}</h2>

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

    <form action="{{ route('admin.artworks.update', $artwork->id) }}" method="POST" enctype="multipart/form-data"
          x-data="{
              isForEvent: '{{ old('is_for_event', $artwork->is_for_event) }}',
              selectedCategories: {{ json_encode($artwork->categories->pluck('id')->toArray()) }}
          }">
        @csrf
        @method('PUT')

        <!-- 🖼️ Informations générales -->
        <div class="mb-6 border-b pb-4">
            <h3 class="text-lg font-semibold text-gray-600 mb-4">{{ __('admin/artworks.details') }}</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.name') }}:</label>
                    <input type="text" name="name" value="{{ old('name', $artwork->name) }}"
                           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring focus:ring-blue-200" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.artist') }}:</label>
                    <select name="artist_id" class="w-full border border-gray-300 px-4 py-2 rounded-lg" required>
                        <option value="">{{ __('admin/artworks.select_artist') }}</option>
                        @foreach($artists as $artist)
                            <option value="{{ $artist->id }}" {{ old('artist_id', $artwork->artist_id) == $artist->id ? 'selected' : '' }}>
                                {{ $artist->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.description') }}:</label>
                <textarea name="description" class="w-full border border-gray-300 px-4 py-2 rounded-lg h-24 focus:ring focus:ring-blue-200">
                    {{ old('description', $artwork->description) }}
                </textarea>
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
                    <input type="number" name="initial_price" value="{{ old('initial_price', $artwork->initial_price) }}"
                           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring focus:ring-blue-200" step="0.01" min="0">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.height') }} (cm):</label>
                    <input type="number" name="height" value="{{ old('height', $artwork->height) }}"
                           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring focus:ring-blue-200" step="0.01" min="0">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.width') }} (cm):</label>
                    <input type="number" name="width" value="{{ old('width', $artwork->width) }}"
                           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring focus:ring-blue-200" step="0.01" min="0">
                </div>
            </div>
        </div>

        <!-- 🎨 Image Upload -->
        <div class="mb-6 border-b pb-4">
            <h3 class="text-lg font-semibold text-gray-600 mb-4">{{ __('admin/artworks.upload_image') }}</h3>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.current_image') }}:</label>
                <img src="{{ asset('storage/artworks/' . basename($artwork->image)) }}"
                     alt="{{ __('admin/artworks.image_alt') }}" class="w-32 h-32 object-cover rounded-lg shadow">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.upload_new') }}:</label>
                <input type="file" name="image" class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring focus:ring-blue-200">
            </div>
        </div>

        <!-- Boutons -->
        <div class="flex justify-between mt-6">
            <a href="{{ route('admin.artworks.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                {{ __('admin/artworks.return') }}
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                {{ __('admin/artworks.save_changes') }}
            </button>
        </div>
    </form>
</div>
@endsection
