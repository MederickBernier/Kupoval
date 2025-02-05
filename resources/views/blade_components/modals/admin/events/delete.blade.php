<div x-cloak x-show="openDeleteModal"
     x-transition
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4"
     @keydown.window.escape="openDeleteModal = false"
     @click.away="openDeleteModal = false">

    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md sm:max-w-lg relative">
        <!-- Bouton de fermeture -->
        <button @click="openDeleteModal = false" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">
            <i class="bi bi-x-lg"></i>
        </button>

        <h2 class="text-xl font-bold text-red-500 mb-4">Confirm Event Deletion</h2>
        <p>You are about to delete <strong x-text="selectedEvent.name"></strong>. This action is irreversible.</p>

        <div class="mt-4">
            <button type="button" @click="openDeleteModal = false"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Cancel
            </button>
            <button @click="deleteEvent()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                Delete
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('eventManager', () => ({
        openDeleteModal: false,
        selectedEvent: {},

        async deleteEvent() {
            if (!this.selectedEvent.id) {
                console.error("No event selected for deletion.");
                return;
            }

            let url = `/admin/events/${this.selectedEvent.id}`;

            try {
                let response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                });

                let data = await response.json();

                if (response.ok) {
                    alert(data.success);
                    this.openDeleteModal = false;
                    window.location.reload();
                } else {
                    alert(data.error || 'Failed to delete event.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An unexpected error occurred.');
            }
        }
    }));
});
</script>
