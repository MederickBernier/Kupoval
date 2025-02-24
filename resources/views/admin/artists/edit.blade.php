@extends('layouts.admin')

@section('title', __('admin/artists.edit'))

@section('content')

<div class="bg-white p-8 rounded-lg shadow-lg max-w-4xl mx-auto">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800">{{ __('admin/artists.edit_title', ['name' => $artist->name]) }}</h2>

    <form action="{{ route('admin.artists.update', $artist) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Grid Layout for Form -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- First Name -->
            <div>
                <label for="first_name" class="block font-semibold text-gray-700">{{ __('admin/artists.first_name') }}</label>
                <input type="text" name="first_name" id="first_name"
                       class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                       value="{{ old('first_name', $artist->first_name) }}">
            </div>

            <!-- Last Name -->
            <div>
                <label for="last_name" class="block font-semibold text-gray-700">{{ __('admin/artists.last_name') }}</label>
                <input type="text" name="last_name" id="last_name"
                       class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                       value="{{ old('last_name', $artist->last_name) }}">
            </div>
        </div>

        <!-- Artist Name (Display Name) -->
        <div>
            <label for="name" class="block font-semibold text-gray-700">{{ __('admin/artists.name') }}</label>
            <input type="text" name="name" id="name"
                   class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                   value="{{ old('name', $artist->name) }}">
        </div>

        <!-- Biography -->
        <div>
            <label for="bio" class="block font-semibold text-gray-700">{{ __('admin/artists.bio') }}</label>
            <textarea name="bio" id="bio"
                      class="editor w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1 h-40">{{ old('bio', $artist->bio) }}</textarea>
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block font-semibold text-gray-700">{{ __('admin/artists.email') }}</label>
            <input type="email" name="email" id="email"
                class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                value="{{ old('email', $artist->email) }}">
        </div>

        <!-- Website -->
        <div>
            <label for="website" class="block font-semibold text-gray-700">{{ __('admin/artists.website') }}</label>
            <input type="url" name="website" id="website"
                class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                value="{{ old('website', $artist->website ?? '') }}"
                onblur="ensureHttps(this)">
        </div>

        <!-- Social Media Links -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="facebook" class="block font-semibold text-gray-700">Facebook</label>
                <input type="url" name="facebook" id="facebook"
                    class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                    value="{{ old('facebook', $artist->facebook ?? '') }}"
                    onblur="ensureHttps(this)">
            </div>
            <div>
                <label for="twitter" class="block font-semibold text-gray-700">Twitter</label>
                <input type="url" name="twitter" id="twitter"
                    class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                    value="{{ old('twitter', $artist->twitter ?? '') }}"
                    onblur="ensureHttps(this)">
            </div>
            <div>
                <label for="instagram" class="block font-semibold text-gray-700">Instagram</label>
                <input type="url" name="instagram" id="instagram"
                    class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                    value="{{ old('instagram', $artist->instagram ?? '') }}"
                    onblur="ensureHttps(this)">
            </div>
            <div>
                <label for="tiktok" class="block font-semibold text-gray-700">TikTok</label>
                <input type="url" name="tiktok" id="tiktok"
                    class="w-full p-3 border rounded-lg focus:ring focus:ring-blue-300 mt-1"
                    value="{{ old('tiktok', $artist->tiktok ?? '') }}"
                    onblur="ensureHttps(this)">
            </div>
        </div>

        <!-- Photo Upload with Preview -->
        <div class="flex items-center space-x-4">
            <div class="w-24 h-24 border rounded-lg overflow-hidden">
                <img id="photoPreview" src="{{ asset($artist->photo) }}" class="w-full h-full object-cover" alt="Current Photo">
            </div>
            <div class="flex-1">
                <label for="photo" class="block font-semibold text-gray-700">{{ __('admin/artists.photo') }}</label>
                <input type="file" name="photo" id="photo" class="w-full p-2 border rounded-lg mt-1" accept="image/*" onchange="previewPhoto(event)">
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center mt-8">
            <a href="{{ route('admin.artists.index') }}" class="px-5 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">
                {{ __('admin/artists.cancel') }}
            </a>
            <button type="submit" class="px-5 py-3 bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-600">
                {{ __('admin/artists.update') }}
            </button>
        </div>
    </form>
</div>

<!-- Live Image Preview Script -->
<script>
function previewPhoto(event) {
    const reader = new FileReader();
    reader.onload = function () {
        document.getElementById('photoPreview').src = reader.result;
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
