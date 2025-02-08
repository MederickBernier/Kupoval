<div x-cloak x-show="openRestoreArtistModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
     x-transition.opacity @keydown.window.escape="openRestoreArtistModal = false" @click.away="openRestoreArtistModal = false">

    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">{{ __('admin/artists.restore_title') }}</h2>
            <button @click="openRestoreArtistModal = false" class="text-gray-600 hover:text-gray-900">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Artist Details -->
        <div class="text-center">
            <img x-bind:src="selectedArtist.photo"
                 alt=""
                 class="w-24 h-24 rounded-full object-cover mx-auto mb-3 shadow-lg">
            <h3 class="text-lg font-semibold" x-text="selectedArtist.name"></h3>
        </div>

        <p class="text-gray-600 text-center">{{ __('admin/artists.confirm_restore') }}</p>

        <!-- Actions -->
        <div class="mt-6 flex justify-end space-x-2">
            <button @click="openRestoreArtistModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">
                {{ __('admin/artists.cancel') }}
            </button>
            <button @click="restoreArtist()" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                {{ __('admin/artists.restore') }}
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('restoreArtistModal', () => ({
        restoreArtist() {
            fetch(this.restoreUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            }).then(response => response.json())
              .then(data => {
                if (data.success) {
                    this.openRestoreArtistModal = false;
                    window.location.reload();
                } else {
                    alert(data.error || "{{ __('admin/artists.restore_failed') }}");
                }
            });
        }
    }));
});
</script>
