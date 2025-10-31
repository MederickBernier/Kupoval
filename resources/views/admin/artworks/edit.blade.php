@extends('layouts.admin')

@section('title', __('admin/artworks.edit_title'))

@section('content')
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-gray-700">{{ __('admin/artworks.edit_title') }}</h2>

        <!-- Display Errors -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.artworks.update', $artwork->slug) }}" method="POST" enctype="multipart/form-data"
            x-data="{
                isForEvent: '{{ old('is_for_event', $artwork->is_for_event) }}',
                selectedCategories: {{ json_encode($artwork->categories->pluck('id')->toArray()) }}
            }">
            @csrf
            @method('PUT')

            <!-- 🖼️ Artwork Details -->
            <div class="mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-gray-600 mb-4">{{ __('admin/artworks.details') }}</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.name') }}:</label>
                        <input type="text" name="name" value="{{ old('name', $artwork->name) }}"
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring focus:ring-blue-200"
                            required>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.artist') }}:</label>
                        <select name="artist_id" class="w-full border border-gray-300 px-4 py-2 rounded-lg" required>
                            <option value="">{{ __('admin/artworks.select_artist') }}</option>
                            @foreach ($artists as $artist)
                                <option value="{{ $artist->id }}"
                                    {{ old('artist_id', $artwork->artist_id) == $artist->id ? 'selected' : '' }}>
                                    {{ $artist->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.description') }}:</label>
                    <textarea name="description"
                        class="editor w-full border border-gray-300 px-4 py-2 rounded-lg h-24 focus:ring focus:ring-blue-200">{{ old('description', $artwork->description) }}</textarea>
                </div>
            </div>

            <!-- 🎨 Series & Artwork Details -->
            <div class="mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-gray-600 mb-4">{{ __('admin/artworks.artwork_details') }}</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.series_name') }}:</label>
                        <input type="text" name="series_name" value="{{ old('series_name', $artwork->series_name) }}"
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg"
                            placeholder="{{ __('admin/artworks.series_name_placeholder') }}">
                        @error('series_name')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.creation_year') }}:</label>
                        <input type="number" name="creation_year"
                            value="{{ old('creation_year', $artwork->creation_year) }}"
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg" min="1000"
                            max="{{ date('Y') + 5 }}" placeholder="{{ date('Y') }}">
                        @error('creation_year')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.medium') }}:</label>
                        <input type="text" name="medium" value="{{ old('medium', $artwork->medium) }}"
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg"
                            placeholder="{{ __('admin/artworks.medium_placeholder') }}">
                        @error('medium')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.condition') }}:</label>
                        <select name="condition" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                            <option value="">{{ __('admin/artworks.select_condition') }}</option>
                            <option value="Excellent"
                                {{ old('condition', $artwork->condition) === 'Excellent' ? 'selected' : '' }}>
                                {{ __('admin/artworks.excellent') }}</option>
                            <option value="Very Good"
                                {{ old('condition', $artwork->condition) === 'Very Good' ? 'selected' : '' }}>
                                {{ __('admin/artworks.very_good') }}</option>
                            <option value="Good"
                                {{ old('condition', $artwork->condition) === 'Good' ? 'selected' : '' }}>
                                {{ __('admin/artworks.good') }}</option>
                            <option value="Fair"
                                {{ old('condition', $artwork->condition) === 'Fair' ? 'selected' : '' }}>
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
                        placeholder="{{ __('admin/artworks.technique_notes_placeholder') }}">{{ old('technique_notes', $artwork->technique_notes) }}</textarea>
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
                        <input type="text" name="dimensions" value="{{ old('dimensions', $artwork->dimensions) }}"
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg"
                            placeholder="{{ __('admin/artworks.dimensions_placeholder') }}">
                        @error('dimensions')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.depth') }}:</label>
                        <input type="text" name="depth" value="{{ old('depth', $artwork->depth) }}"
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg"
                            placeholder="{{ __('admin/artworks.depth_placeholder') }}">
                        @error('depth')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.weight') }}:</label>
                        <input type="text" name="weight" value="{{ old('weight', $artwork->weight) }}"
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg"
                            placeholder="{{ __('admin/artworks.weight_placeholder') }}">
                        @error('weight')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.edition_info') }}:</label>
                        <input type="text" name="edition_info" value="{{ old('edition_info', $artwork->edition_info) }}"
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg"
                            placeholder="{{ __('admin/artworks.edition_info_placeholder') }}">
                        @error('edition_info')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center space-x-2">
                            <input type="hidden" name="is_framed" value="0">
                            <input type="checkbox" name="is_framed" value="1" class="form-checkbox text-blue-600"
                                {{ old('is_framed', $artwork->is_framed) ? 'checked' : '' }}>
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
                        placeholder="{{ __('admin/artworks.framing_details_placeholder') }}">{{ old('framing_details', $artwork->framing_details) }}</textarea>
                    @error('framing_details')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.provenance') }}:</label>
                    <textarea name="provenance" class="w-full border border-gray-300 px-4 py-2 rounded-lg h-20"
                        placeholder="{{ __('admin/artworks.provenance_placeholder') }}">{{ old('provenance', $artwork->provenance) }}</textarea>
                    @error('provenance')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.care_instructions') }}:</label>
                    <textarea name="care_instructions" class="w-full border border-gray-300 px-4 py-2 rounded-lg h-20"
                        placeholder="{{ __('admin/artworks.care_instructions_placeholder') }}">{{ old('care_instructions', $artwork->care_instructions) }}</textarea>
                    @error('care_instructions')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- 🏷️ Category Selection -->
            <div class="mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-gray-600 mb-4">{{ __('admin/artworks.categories') }}</h3>

                <input type="hidden" name="categories" x-bind:value="selectedCategories.join(',')">

                <div class="flex flex-wrap gap-2">
                    @foreach ($categories as $category)
                        <button type="button"
                            @click="selectedCategories.includes({{ $category->id }})
                            ? selectedCategories.splice(selectedCategories.indexOf({{ $category->id }}), 1)
                            : selectedCategories.push({{ $category->id }})"
                            x-bind:class="selectedCategories.includes({{ $category->id }}) ?
                                'bg-blue-500 text-white' :
                                'bg-gray-200 text-gray-700'"
                            class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-300 transition">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- 📏 Dimensions & Price -->
            <div class="mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-gray-600 mb-4">{{ __('admin/artworks.size_pricing') }}</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.price') }}
                            ($):</label>
                        <input type="number" name="initial_price"
                            value="{{ old('initial_price', $artwork->initial_price) }}"
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring focus:ring-blue-200"
                            step="0.01" min="0">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.height') }}
                            (cm):</label>
                        <input type="number" name="height" value="{{ old('height', $artwork->height) }}"
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring focus:ring-blue-200"
                            step="0.01" min="0">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.width') }}
                            (cm):</label>
                        <input type="number" name="width" value="{{ old('width', $artwork->width) }}"
                            class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring focus:ring-blue-200"
                            step="0.01" min="0">
                    </div>
                </div>
            </div>

            <!-- 🎨 Image Upload -->
            <div class="mb-6 border-b pb-4">
                <h3 class="text-lg font-semibold text-gray-600 mb-4">{{ __('admin/artworks.upload_image') }}</h3>

                <div class="mb-4">
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.current_image') }}:</label>
                    <img src="{{ asset('storage/' . $artwork->image) }}" alt="{{ __('admin/artworks.image_alt') }}"
                        class="w-32 h-32 object-cover rounded-lg shadow">
                </div>

                <div>
                    <label
                        class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.upload_new') }}:</label>
                    <input type="file" name="image"
                        class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring focus:ring-blue-200">
                </div>
            </div>

            <!-- 📢 Sale & Event Status -->
            <div class="mb-6 border border-gray-300 rounded-lg p-4 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-600 mb-4">{{ __('admin/artworks.sale_event_options') }}</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.for_sale') }}:</label>
                        <select name="is_on_sale" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                            <option value="1" {{ $artwork->is_on_sale ? 'selected' : '' }}>
                                {{ __('admin/artworks.yes') }}</option>
                            <option value="0" {{ !$artwork->is_on_sale ? 'selected' : '' }}>
                                {{ __('admin/artworks.no') }}</option>
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-semibold text-gray-700 mb-1">{{ __('admin/artworks.featured') }}:</label>
                        <select name="is_featured" class="w-full border border-gray-300 px-4 py-2 rounded-lg">
                            <option value="1" {{ $artwork->is_featured ? 'selected' : '' }}>
                                {{ __('admin/artworks.yes') }}</option>
                            <option value="0" {{ !$artwork->is_featured ? 'selected' : '' }}>
                                {{ __('admin/artworks.no') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                {{ __('admin/artworks.save_changes') }}
            </button>
        </form>
    </div>
@endsection
