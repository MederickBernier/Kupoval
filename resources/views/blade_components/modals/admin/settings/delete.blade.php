<div x-cloak x-show="openDeleteModal"
     x-transition.opacity
     class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
     @keydown.window.escape="openDeleteModal = false"
     @click.away="openDeleteModal = false">

    <div class="bg-white p-6 rounded-lg shadow-lg w-96" x-data="{ confirmationText: '' }">
        <h2 class="text-lg font-semibold text-red-600 mb-4">{{ __('admin/categories.delete_title') }}</h2>
        <p>
            {{ __('admin/categories.delete_confirmation') }}
            <strong x-text="selectedCategory ? selectedCategory.name : 'Unknown'"></strong>.
            {{ __('admin/categories.irreversible_action') }}
        </p>

        <!-- Confirmation Input -->
        <div class="mt-4">
            <label for="confirmDeleteName" class="font-semibold">{{ __('admin/categories.confirm_name_label') }}</label>
            <input type="text" id="confirmDeleteName" class="w-full p-2 border rounded mt-1"
                   placeholder="{{ __('admin/categories.confirm_name_placeholder') }}"
                   x-model="confirmationText">
        </div>

        <div class="flex justify-end space-x-2 mt-6">
            <button @click="openDeleteModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">
                {{ __('public/interface.cancel') }}
            </button>
            <form x-bind:action="selectedCategory && selectedCategory.id ? ('/admin/categories/' + selectedCategory.id) : '#'"
                  method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 bg-red-500 text-white rounded disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="confirmationText !== (selectedCategory ? selectedCategory.name : '')">
                    {{ __('admin/categories.delete_button') }}
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('categoryManagement', () => ({
        selectedCategory: null,
        openDeleteModal: false,

        selectCategory(category) {
            this.selectedCategory = category;
            this.openDeleteModal = true; // Ensures the modal opens properly
        }
    }));
});
</script>
