<div x-cloak x-show="openDeleteArtworkModal"
     x-transition
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4"
     @keydown.window.escape="openDeleteArtworkModal = false"
     @click.away="openDeleteArtworkModal = false">

    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md sm:max-w-lg relative">
        <!-- Bouton de fermeture -->
        <button @click="openDeleteArtworkModal = false" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">
            <i class="bi bi-x-lg"></i>
        </button>

        <h2 class="text-xl font-bold text-red-500 mb-4 text-center">Confirm Artwork Deletion</h2>

        <!-- Affichage des détails de l'œuvre -->
        <div class="flex flex-col items-center">
            <!-- Gestion d'affichage de l'image -->
            <template x-if="selectedArtwork.image">
                <img :src="'/storage/artworks/' + selectedArtwork.image.split('/').pop()"
                     alt="Artwork Image"
                     class="w-32 h-32 object-cover rounded-lg shadow-md mb-4">
            </template>
            <template x-if="!selectedArtwork.image">
                <img src="{{ asset('images/placeholder.png') }}"
                     alt="No Image Available"
                     class="w-32 h-32 object-cover rounded-lg shadow-md mb-4">
            </template>

            <p class="text-lg font-semibold text-center" x-text="selectedArtwork.name"></p>
            <p class="text-sm text-gray-500 text-center" x-text="selectedArtwork.description"></p>
        </div>

        <p class="mt-4 text-sm text-gray-600 text-center">
            To confirm deletion, type "<strong x-text="selectedArtwork.name"></strong>" below:
        </p>

        <input type="text" x-model="deleteConfirmation"
               class="w-full border px-4 py-2 rounded-lg mt-2 text-center"
               placeholder="Type the artwork name">

        <form x-bind:action="deleteUrl" method="POST" class="mt-4">
            @csrf
            @method('DELETE')

            <div class="flex justify-end space-x-2">
                <button type="button" @click="openDeleteArtworkModal = false"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" :disabled="deleteConfirmation !== selectedArtwork.name"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 disabled:bg-gray-400 disabled:cursor-not-allowed">
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>
