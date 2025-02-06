<div x-cloak x-show="openDeleteModal"
     x-transition.opacity
     class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
     @keydown.window.escape="openDeleteModal = false"
     @click.away="openDeleteModal = false">

    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold text-red-600 mb-4">{{ __('admin/users.confirm_delete_title') }}</h2>
        <p>
            {{ __('admin/users.confirm_delete_message') }}
            <strong x-text="selectedUser ? selectedUser.username : 'Unknown'"></strong>.
            {{ __('admin/users.irreversible_action') }}
        </p>

        <!-- Confirmation Input -->
        <div class="mt-4">
            <label for="confirmName" class="font-semibold">{{ __('admin/users.enter_full_name') }}</label>
            <input type="text" id="confirmName" class="w-full p-2 border rounded mt-1"
                   placeholder="{{ __('admin/users.type_full_name_placeholder') }}" x-model="confirmationText">
        </div>

        <div class="flex justify-end space-x-2 mt-6">
            <button @click="openDeleteModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">
                {{ __('admin/users.cancel') }}
            </button>
            <button
                :disabled="confirmationText !== (selectedUser ? selectedUser.username : '')"
                class="px-4 py-2 bg-red-500 text-white rounded disabled:opacity-50 disabled:cursor-not-allowed">
                {{ __('admin/users.confirm_delete') }}
            </button>
        </div>
    </div>
</div>
