<div x-cloak x-show="openForceDeleteArtistModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
     x-transition.opacity @keydown.window.escape="openForceDeleteArtistModal = false" @click.away="openForceDeleteArtistModal = false">

    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-red-600">{{ __('admin/artists.force_delete_title') }}</h2>
            <button @click="openForceDeleteArtistModal = false" class="text-gray-600 hover:text-gray-900">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Confirmation Message -->
        <p class="text-gray-800">
            {{ __('admin/artists.confirm_force_delete') }}
            <strong x-text="selectedArtist ? selectedArtist.name : 'Unknown'"></strong>?
        </p>

        <!-- Actions -->
        <div class="mt-6 flex justify-end space-x-2">
            <button @click="openForceDeleteArtistModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">
                {{ __('admin/artists.cancel') }}
            </button>
            <button @click="forceDeleteArtist()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                {{ __('admin/artists.force_delete') }}
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('artistManager', () => ({
        openForceDeleteArtistModal: false,
        selectedArtist: { id: '', name: '', slug: '', deleteUrl: '' },

        setForceDeleteArtist(artist, url) {
            this.selectedArtist = { ...artist, deleteUrl: url };
            this.openForceDeleteArtistModal = true;
        },

        forceDeleteArtist() {
            if (!this.selectedArtist.slug) {
                console.error("No artist selected for force deletion.");
                return;
            }

            fetch(this.selectedArtist.deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to delete artist.');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    this.openForceDeleteArtistModal = false;
                    alert(data.success);
                    window.location.reload();
                } else {
                    alert(data.error || "An error occurred while deleting the artist.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Unexpected error occurred.");
            });
        }
    }));
});
</script>
