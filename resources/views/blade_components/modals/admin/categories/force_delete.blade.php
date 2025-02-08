<div x-cloak x-show="openForceDeleteModal"
     x-transition.opacity
     class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
     @keydown.window.escape="openForceDeleteModal = false"
     @click.away="openForceDeleteModal = false">

    <div class="bg-white p-6 rounded-lg shadow-lg w-96" x-data="{ confirmationText: '' }">
        <h2 class="text-lg font-semibold text-red-700 mb-4">{{ __('admin/categories.force_delete_title') }}</h2>
        <p>
            {{ __('admin/categories.force_delete_confirmation') }}
            <strong x-text="selectedCategory ? selectedCategory.name : 'Unknown'"></strong>.
        </p>

        <!-- Validation Input -->
        <div class="mt-4">
            <label for="confirmForceDeleteName" class="block text-sm font-medium text-gray-700">
                {{ __('admin/categories.confirm_name_label') }}
            </label>
            <input type="text" id="confirmForceDeleteName"
                   x-model="confirmationText"
                   class="w-full border px-4 py-2 rounded-lg mt-2"
                   placeholder="{{ __('admin/categories.confirm_name_placeholder') }}">
        </div>

        <div class="flex justify-end space-x-2 mt-6">
            <button @click="openForceDeleteModal = false"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded">
                {{ __('public/interface.cancel') }}
            </button>
            <form x-bind:action="selectedCategory && selectedCategory.id ? ('/admin/categories/force-delete/' + selectedCategory.id) : '#'"
                  method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="confirmationText !== (selectedCategory ? selectedCategory.name : '')">
                    {{ __('admin/categories.force_delete_button') }}
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('categoryManagement', () => ({
        selectedCategory: null,
        openForceDeleteModal: false,

        selectCategory(category) {
            this.selectedCategory = category;
            this.openForceDeleteModal = true; // Ensures the modal opens properly
        }
    }));
});
</script>
