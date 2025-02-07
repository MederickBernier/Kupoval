<div x-cloak x-show="openRestoreModal"
     class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold text-green-600 mb-4">{{ __('admin/categories.restore_title') }}</h2>
        <p>
            {{ __('admin/categories.restore_confirmation') }}
            <strong x-text="selectedCategory ? selectedCategory.name : 'Unknown'"></strong>.
        </p>

        <div class="flex justify-end space-x-2 mt-6">
            <button @click="openRestoreModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">
                {{ __('public/interface.cancel') }}
            </button>
            <form :action="'/admin/categories/restore/' + selectedCategory.id" method="POST">
                @csrf
                <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                    {{ __('admin/categories.restore_button') }}
                </button>
            </form>
        </div>
    </div>
</div>
