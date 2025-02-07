<div x-cloak x-show="openForceDeleteModal"
     class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold text-red-700 mb-4">{{ __('admin/categories.force_delete_title') }}</h2>
        <p>
            {{ __('admin/categories.force_delete_confirmation') }}
            <strong x-text="selectedCategory ? selectedCategory.name : 'Unknown'"></strong>.
        </p>

        <div class="flex justify-end space-x-2 mt-6">
            <button @click="openForceDeleteModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">
                {{ __('public/interface.cancel') }}
            </button>
            <form x-bind:action="'/admin/categories/force-delete/' + selectedCategory.id" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 bg-red-700 text-white rounded hover:bg-red-800">
                    {{ __('admin/categories.force_delete_button') }}
                </button>
            </form>
        </div>
    </div>
</div>
