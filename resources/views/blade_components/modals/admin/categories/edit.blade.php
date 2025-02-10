<div x-cloak x-show="openEditModal"
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-bold text-blue-500 mb-4">{{ __('admin/categories.edit_title') }}</h2>

        <form :action="'/admin/categories/' + selectedCategory.id" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">{{ __('admin/categories.name') }}</label>
                <input type="text" name="name" class="w-full border px-4 py-2 rounded-lg"
                       x-model="selectedCategory.name" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">{{ __('admin/categories.description') }}</label>
                <textarea name="description" class="editor w-full border px-4 py-2 rounded-lg"
                          x-model="selectedCategory.description"></textarea>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" @click="openEditModal = false" class="px-4 py-2 border rounded">
                    {{ __('public/interface.cancel') }}
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    {{ __('admin/categories.update_button') }}
                </button>
            </div>
        </form>
    </div>
</div>
