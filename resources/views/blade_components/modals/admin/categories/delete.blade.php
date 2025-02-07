<div x-cloak x-show="openDeleteModal"
     class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold text-red-600 mb-4">{{ __('admin/categories.delete_title') }}</h2>
        <p>
            {{ __('admin/categories.delete_confirmation') }}
            <strong x-text="selectedCategory ? selectedCategory.name : 'Unknown'"></strong>.
        </p>

        <div class="flex justify-end space-x-2 mt-6">
            <button @click="openDeleteModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">
                {{ __('public/interface.cancel') }}
            </button>
            <form x-bind:action="'/admin/categories/' + selectedCategory.id" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                    {{ __('admin/categories.delete_button') }}
                </button>
            </form>
        </div>
    </div>
</div>
