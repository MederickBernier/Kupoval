<div x-cloak x-show="openDeleteArtistModal" x-transition.opacity class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
     @keydown.window.escape="openDeleteArtistModal = false"
     @click.away="openDeleteArtistModal = false">

    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold text-red-600 mb-4">{{ __('admin/artists.confirm_delete_title') }}</h2>
        <p>
            {{ __('admin/artists.confirm_delete_message') }}
            <strong x-text="selectedArtist ? selectedArtist.name : 'Unknown'"></strong>?
        </p>

        <div class="flex justify-end space-x-2 mt-6">
            <button @click="openDeleteArtistModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">
                {{ __('admin/artists.cancel') }}
            </button>
            <form :action="deleteUrl" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded">
                    {{ __('admin/artists.confirm_delete') }}
                </button>
            </form>
        </div>
    </div>
</div>
