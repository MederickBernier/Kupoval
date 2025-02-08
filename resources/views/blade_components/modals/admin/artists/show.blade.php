<div x-cloak x-show="openShowArtistModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
     x-transition.opacity @keydown.window.escape="openShowArtistModal = false" @click.away="openShowArtistModal = false">

    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">{{ __('admin/artists.view_title') }}</h2>
            <button @click="openShowArtistModal = false" class="text-gray-600 hover:text-gray-900">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Artist Details -->
        <div class="text-center">
            <img x-bind:src="selectedArtist.photo ? '{{ asset('') }}' + selectedArtist.photo : '{{ asset('images/default-profile.png') }}'"
                 alt=""
                 class="w-24 h-24 rounded-full object-cover mx-auto mb-3 shadow-lg">
            <h3 class="text-lg font-semibold" x-text="selectedArtist.name"></h3>
            <p class="text-gray-600 mt-1" x-text="selectedArtist.bio"></p>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-end space-x-2">
            <button @click="openShowArtistModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">
                {{ __('admin/artists.close') }}
            </button>
            <a :href="selectedArtist.editUrl" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                {{ __('admin/artists.edit') }}
            </a>
        </div>
    </div>
</div>
