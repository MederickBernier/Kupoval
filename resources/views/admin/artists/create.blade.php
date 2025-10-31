@extends('layouts.admin')

@section('title', __('admin/artists.create'))

@section('content')

    <div class="bg-white p-8 rounded-lg shadow-lg max-w-4xl mx-auto">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">{{ __('admin/artists.create_title') }}</h2>

        <form action="{{ route('admin.artists.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Grid Layout for Form -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- First Name -->
                <div>
                    <label for="first_name"
                        class="block font-semibold text-gray-700">{{ __('admin/artists.first_name') }}</label>
                    <input type="text" name="first_name" id="first_name"
                        class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                        value="{{ old('first_name') }}">
                    @error('first_name')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name"
                        class="block font-semibold text-gray-700">{{ __('admin/artists.last_name') }}</label>
                    <input type="text" name="last_name" id="last_name"
                        class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                        value="{{ old('last_name') }}">
                    @error('last_name')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Artist Name (Display Name) -->
            <div>
                <label for="name" class="block font-semibold text-gray-700">{{ __('admin/artists.name') }}</label>
                <input type="text" name="name" id="name"
                    class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1" value="{{ old('name') }}">
                @error('name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Biography -->
            <div>
                <label for="bio" class="block font-semibold text-gray-700">{{ __('admin/artists.bio') }}</label>
                <textarea name="bio" id="bio"
                    class="editor w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1 h-40">{{ old('bio') }}</textarea>
                @error('bio')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Professional Profile Section -->
            <div class="border-t pt-6 mt-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">{{ __('admin/artists.professional_profile') }}</h3>

                <!-- Artist Statement -->
                <div>
                    <label for="artist_statement"
                        class="block font-semibold text-gray-700">{{ __('admin/artists.artist_statement') }}</label>
                    <textarea name="artist_statement" id="artist_statement"
                        class="editor w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1 h-32"
                        placeholder="{{ __('admin/artists.artist_statement_placeholder') }}">{{ old('artist_statement') }}</textarea>
                    @error('artist_statement')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Exhibition History -->
                <div>
                    <label for="exhibition_history"
                        class="block font-semibold text-gray-700">{{ __('admin/artists.exhibition_history') }}</label>
                    <textarea name="exhibition_history" id="exhibition_history"
                        class="editor w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1 h-40"
                        placeholder="{{ __('admin/artists.exhibition_history_placeholder') }}">{{ old('exhibition_history') }}</textarea>
                    @error('exhibition_history')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Awards -->
                <div>
                    <label for="awards"
                        class="block font-semibold text-gray-700">{{ __('admin/artists.awards') }}</label>
                    <textarea name="awards" id="awards"
                        class="editor w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1 h-32"
                        placeholder="{{ __('admin/artists.awards_placeholder') }}">{{ old('awards') }}</textarea>
                    @error('awards')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grid Layout for Professional Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Studio Location -->
                    <div>
                        <label for="studio_location"
                            class="block font-semibold text-gray-700">{{ __('admin/artists.studio_location') }}</label>
                        <input type="text" name="studio_location" id="studio_location"
                            class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                            placeholder="{{ __('admin/artists.studio_location_placeholder') }}"
                            value="{{ old('studio_location') }}">
                        @error('studio_location')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Experience Years -->
                    <div>
                        <label for="experience_years"
                            class="block font-semibold text-gray-700">{{ __('admin/artists.experience_years') }}</label>
                        <input type="number" name="experience_years" id="experience_years"
                            class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1" min="0"
                            max="100" placeholder="{{ __('admin/artists.experience_years_placeholder') }}"
                            value="{{ old('experience_years') }}">
                        @error('experience_years')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Specialties -->
                <div>
                    <label for="specialties"
                        class="block font-semibold text-gray-700">{{ __('admin/artists.specialties') }}</label>
                    <input type="text" name="specialties" id="specialties"
                        class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                        placeholder="{{ __('admin/artists.specialties_placeholder') }}" value="{{ old('specialties') }}">
                    <p class="text-sm text-gray-600 mt-1">{{ __('admin/artists.comma_separated') }}</p>
                    @error('specialties')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Techniques -->
                <div>
                    <label for="techniques"
                        class="block font-semibold text-gray-700">{{ __('admin/artists.techniques') }}</label>
                    <input type="text" name="techniques" id="techniques"
                        class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                        placeholder="{{ __('admin/artists.techniques_placeholder') }}" value="{{ old('techniques') }}">
                    <p class="text-sm text-gray-600 mt-1">{{ __('admin/artists.comma_separated') }}</p>
                    @error('techniques')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Profile Video URL -->
                <div>
                    <label for="profile_video_url"
                        class="block font-semibold text-gray-700">{{ __('admin/artists.profile_video_url') }}</label>
                    <input type="url" name="profile_video_url" id="profile_video_url"
                        class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                        placeholder="{{ __('admin/artists.profile_video_placeholder') }}"
                        value="{{ old('profile_video_url') }}" onblur="ensureHttps(this)">
                    @error('profile_video_url')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Contact & Social Media Section -->
            <div class="border-t pt-6 mt-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">{{ __('admin/artists.contact_social') }}</h3>

                <!-- Email -->
                <div>
                    <label for="email"
                        class="block font-semibold text-gray-700">{{ __('admin/artists.email') }}</label>
                    <input type="email" name="email" id="email"
                        class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                        value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Website -->
                <div>
                    <label for="website"
                        class="block font-semibold text-gray-700">{{ __('admin/artists.website') }}</label>
                    <input type="url" name="website" id="website"
                        class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                        onblur="ensureHttps(this)">
                </div>

                <!-- Social Media Links -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="facebook" class="block font-semibold text-gray-700">Facebook</label>
                        <input type="url" name="facebook" id="facebook"
                            class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                            onblur="ensureHttps(this)">
                    </div>
                    <div>
                        <label for="twitter" class="block font-semibold text-gray-700">Twitter</label>
                        <input type="url" name="twitter" id="twitter"
                            class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                            onblur="ensureHttps(this)">
                    </div>
                    <div>
                        <label for="instagram" class="block font-semibold text-gray-700">Instagram</label>
                        <input type="url" name="instagram" id="instagram"
                            class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                            onblur="ensureHttps(this)">
                    </div>
                    <div>
                        <label for="tiktok" class="block font-semibold text-gray-700">TikTok</label>
                        <input type="url" name="tiktok" id="tiktok"
                            class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                            onblur="ensureHttps(this)">
                    </div>
                    <div>
                        <label for="youtube" class="block font-semibold text-gray-700">YouTube</label>
                        <input type="url" name="youtube" id="youtube"
                            class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                            value="{{ old('youtube') }}" onblur="ensureHttps(this)">
                        @error('youtube')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Photo Upload with Preview -->
                <div class="flex items-center space-x-4">
                    <div class="w-24 h-24 border rounded-lg overflow-hidden bg-gray-200 flex items-center justify-center">
                        <img id="photoPreview" src="" class="w-full h-full object-cover hidden">
                        <span id="noPhotoText" class="text-gray-500">{{ __('admin/artists.no_photo') }}</span>
                    </div>
                    <div class="flex-1">
                        <label for="photo"
                            class="block font-semibold text-gray-700">{{ __('admin/artists.photo') }}</label>
                        <input type="file" name="photo" id="photo" class="w-full p-2 border rounded-lg mt-1"
                            accept="image/*" onchange="previewPhoto(event)">
                        @error('photo')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center mt-8">
                    <a href="{{ route('admin.artists.index') }}"
                        class="px-5 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">
                        {{ __('admin/artists.cancel') }}
                    </a>
                    <button type="submit"
                        class="px-5 py-3 bg-green-500 text-white font-bold rounded-lg hover:bg-green-600">
                        {{ __('admin/artists.save') }}
                    </button>
                </div>
        </form>
    </div>

    <!-- Live Image Preview Script -->
    <script>
        function previewPhoto(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const img = document.getElementById('photoPreview');
                const text = document.getElementById('noPhotoText');
                img.src = reader.result;
                img.classList.remove('hidden');
                text.classList.add('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function ensureHttps(input) {
            if (input.value.trim() !== '' && !/^https?:\/\//i.test(input.value)) {
                input.value = 'https://' + input.value.trim();
            }
        }
    </script>

@endsection
