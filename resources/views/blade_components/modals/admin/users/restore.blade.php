<div x-cloak x-show="openRestoreModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
     x-transition.opacity @keydown.window.escape="openRestoreModal = false" @click.away="openRestoreModal = false">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold text-green-600 mb-4">{{ __('admin/users.confirm_restore_title') }}</h2>
        <p>
            {{ __('admin/users.confirm_restore_message') }}
            <strong x-text="selectedUser ? selectedUser.username : '{{ __('admin/users.unknown') }}'"></strong>?
        </p>

        <div class="flex justify-end space-x-2 mt-6">
            <button @click="openRestoreModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">
                {{ __('admin/users.cancel') }}
            </button>
            <button @click="restoreUser()" class="px-4 py-2 bg-green-500 text-white rounded">
                {{ __('admin/users.confirm_restore') }}
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('restoreUserModal', () => ({
        selectedUser: null,
        openRestoreModal: false,

        restoreUser() {
            if (!this.selectedUser || !this.selectedUser.id) {
                return;
            }

            fetch(`{{ url('/admin/users/restore') }}/${this.selectedUser.id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.openRestoreModal = false;
                    alert(data.success);
                    window.location.reload();
                } else {
                    alert(data.error || '{{ __('admin/users.restore_failed') }}');
                }
            })
            .catch(error => {
                alert('{{ __('admin/users.unexpected_error') }}');
            });
        }
    }));
});
</script>
