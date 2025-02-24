<div x-cloak x-show="openDeleteModal"
     x-transition
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4"
     @keydown.window.escape="openDeleteModal = false"
     @click.away="openDeleteModal = false">

    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md sm:max-w-lg relative">
        <!-- Close Button -->
        <button @click="openDeleteModal = false" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">
            <i class="bi bi-x-lg"></i>
        </button>

        <h2 class="text-xl font-bold text-red-500 mb-4">{{ __('admin/settings.delete_title') }}</h2>
        <p>{{ __('admin/settings.delete_confirmation') }} <strong x-text="selectedSetting.key"></strong>. {{ __('admin/settings.irreversible_action') }}</p>

        <div class="mt-4">
            <button type="button" @click="openDeleteModal = false"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                {{ __('admin/settings.cancel') }}
            </button>
            <button @click="confirmDelete()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                {{ __('admin/settings.delete') }}
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('settingsManager', () => ({
        openEditModal: false,
        selectedSetting: { id: '', key: '', value: '' },

        async updateSetting() {
            if (!this.selectedSetting.id) {
                alert("{{ __('admin/settings.error_no_selection') }}");
                return;
            }

            let url = `/admin/settings/${this.selectedSetting.id}`;

            try {
                let response = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ value: this.selectedSetting.value ?? '' }) // Allows empty values
                });

                let data = await response.json();

                if (response.ok) {
                    alert(data.success);
                    this.openEditModal = false;
                    window.location.reload();
                } else {
                    alert(data.error || "{{ __('admin/settings.update_failed') }}");
                }
            } catch (error) {
                console.error('Error:', error);
                alert("{{ __('admin/settings.unexpected_error') }}");
            }
        }
    }));
});
</script>
