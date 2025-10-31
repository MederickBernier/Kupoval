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
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.name') }}:</label>
                        <input type="text" name="name" class="w-full border border-gray-300 px-4 py-2 rounded-lg"
                            required>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.artist') }}:</label>
                        <select name="artist_id" class="w-full border border-gray-300 px-4 py-2 rounded-lg" required>
                            <option value="">{{ __('admin/artworks.select_artist') }}</option>
                            @foreach ($artists as $artist)
                                <option value="{{ $artist->id }}">{{ $artist->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.description') }}:</label>
                    <textarea name="description" class="editor w-full border border-gray-300 px-4 py-2 rounded-lg h-24"></textarea>
                </div>
            </div>

            <!-- � Series & Artwork Details -->
            <div class="mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-gray-600 mb-4">{{ __('admin/artworks.artwork_details') }}</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.series_name') }}:</label>
                        <input type="text" name="series_name" class="w-full border border-gray-300 px-4 py-2 rounded-lg"
                            placeholder="{{ __('admin/artworks.series_name_placeholder') }}"
                            value="{{ old('series_name') }}">
                        @error('series_name')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.creation_year') }}:</label>
                        <input type="number" name="creation_year"
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg" min="1000"
                            max="{{ date('Y') + 5 }}" placeholder="{{ date('Y') }}"
                            value="{{ old('creation_year') }}">
                        @error('creation_year')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.medium') }}:</label>
                        <input type="text" name="medium" class="w-full border border-gray-300 px-4 py-2 rounded-lg"
                            placeholder="{{ __('admin/artworks.medium_placeholder') }}" value="{{ old('medium') }}">
                        @error('medium')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.condition') }}:</label>
                        <select name="condition" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                            <option value="">{{ __('admin/artworks.select_condition') }}</option>
                            <option value="Excellent" {{ old('condition') === 'Excellent' ? 'selected' : '' }}>
                                {{ __('admin/artworks.excellent') }}</option>
                            <option value="Very Good" {{ old('condition') === 'Very Good' ? 'selected' : '' }}>
                                {{ __('admin/artworks.very_good') }}</option>
                            <option value="Good" {{ old('condition') === 'Good' ? 'selected' : '' }}>
                                {{ __('admin/artworks.good') }}</option>
                            <option value="Fair" {{ old('condition') === 'Fair' ? 'selected' : '' }}>
                                {{ __('admin/artworks.fair') }}</option>
                        </select>
                        @error('condition')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.technique_notes') }}:</label>
                    <textarea name="technique_notes" class="w-full border border-gray-300 px-4 py-2 rounded-lg h-20"
                        placeholder="{{ __('admin/artworks.technique_notes_placeholder') }}">{{ old('technique_notes') }}</textarea>
                    @error('technique_notes')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- 📐 Detailed Dimensions & Physical Properties -->
            <div class="mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-gray-600 mb-4">{{ __('admin/artworks.physical_details') }}</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.dimensions') }}:</label>
                        <input type="text" name="dimensions" class="w-full border border-gray-300 px-4 py-2 rounded-lg"
                            placeholder="{{ __('admin/artworks.dimensions_placeholder') }}"
                            value="{{ old('dimensions') }}">
                        @error('dimensions')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.depth') }}:</label>
                        <input type="text" name="depth" class="w-full border border-gray-300 px-4 py-2 rounded-lg"
                            placeholder="{{ __('admin/artworks.depth_placeholder') }}" value="{{ old('depth') }}">
                        @error('depth')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.weight') }}:</label>
                        <input type="text" name="weight" class="w-full border border-gray-300 px-4 py-2 rounded-lg"
                            placeholder="{{ __('admin/artworks.weight_placeholder') }}" value="{{ old('weight') }}">
                        @error('weight')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.edition_info') }}:</label>
                        <input type="text" name="edition_info" class="w-full border border-gray-300 px-4 py-2 rounded-lg"
                            placeholder="{{ __('admin/artworks.edition_info_placeholder') }}"
                            value="{{ old('edition_info') }}">
                        @error('edition_info')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2">
                            <input type="hidden" name="is_framed" value="0">
                            <input type="checkbox" name="is_framed" value="1" class="form-checkbox text-blue-600"
                                {{ old('is_framed') ? 'checked' : '' }}>
                            <span class="text-sm font-semibold text-gray-700">{{ __('admin/artworks.is_framed') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- 📋 Additional Information -->
            <div class="mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-gray-600 mb-4">{{ __('admin/artworks.additional_info') }}</h3>

                <div class="mt-4">
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.framing_details') }}:</label>
                    <textarea name="framing_details" class="w-full border border-gray-300 px-4 py-2 rounded-lg h-20"
                        placeholder="{{ __('admin/artworks.framing_details_placeholder') }}">{{ old('framing_details') }}</textarea>
                    @error('framing_details')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.provenance') }}:</label>
                    <textarea name="provenance" class="w-full border border-gray-300 px-4 py-2 rounded-lg h-20"
                        placeholder="{{ __('admin/artworks.provenance_placeholder') }}">{{ old('provenance') }}</textarea>
                    @error('provenance')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.care_instructions') }}:</label>
                    <textarea name="care_instructions" class="w-full border border-gray-300 px-4 py-2 rounded-lg h-20"
                        placeholder="{{ __('admin/artworks.care_instructions_placeholder') }}">{{ old('care_instructions') }}</textarea>
                    @error('care_instructions')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- �🏷️ Sélection des catégories (Updated) -->
            <div class="mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-gray-600 mb-4">{{ __('admin/artworks.categories') }}</h3>

                <div class="flex flex-wrap gap-2">
                    @foreach ($categories as $category)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                x-model="selectedCategories" class="form-checkbox text-blue-600">
                            <span>{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 📏 Dimensions & Prix -->
            <div class="mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-gray-600 mb-4">{{ __('admin/artworks.size_pricing') }}</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.price') }}
                            ($):</label>
                        <input type="number" name="initial_price"
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg" step="0.01" min="0">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.height') }}
                            (cm):</label>
                        <input type="number" name="height" class="w-full border border-gray-300 px-4 py-2 rounded-lg"
                            step="0.01" min="0">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.width') }}
                            (cm):</label>
                        <input type="number" name="width" class="w-full border border-gray-300 px-4 py-2 rounded-lg"
                            step="0.01" min="0">
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
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.for_sale') }}:</label>
                        <select name="is_on_sale" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                            <option value="1">{{ __('admin/artworks.yes') }}</option>
                            <option value="0" selected>{{ __('admin/artworks.no') }}</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.featured') }}:</label>
                        <select name="is_featured" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                            <option value="1">{{ __('admin/artworks.yes') }}</option>
                            <option value="0" selected>{{ __('admin/artworks.no') }}</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.for_event') }}:</label>
                    <select name="is_for_event" x-model="isForEvent"
                        class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                        <option value="0" selected>{{ __('admin/artworks.no') }}</option>
                        <option value="1">{{ __('admin/artworks.yes') }}</option>
                    </select>
                </div>

                <div class="mt-4" x-show="isForEvent === '1'" x-transition>
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.select_event') }}:</label>
                    <select name="event_id" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                        <option value="">{{ __('admin/artworks.select_event_placeholder') }}</option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id }}">{{ $event->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex justify-end space-x-2">
                <a href="{{ route('admin.artworks.index') }}"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    {{ __('admin/artworks.cancel') }}
                </a>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                    {{ __('admin/artworks.save') }}
                </button>
            </div>
        </form>
    </div>
@endsection
