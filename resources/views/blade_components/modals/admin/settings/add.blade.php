<div x-cloak x-show="openAddModal"
     x-transition
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4"
     @keydown.window.escape="openAddModal = false"
     @click.away="openAddModal = false">

    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md sm:max-w-lg">
        <button @click="openAddModal = false" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">
            <i class="bi bi-x-lg"></i>
        </button>

        <h2 class="text-xl font-bold text-green-500 mb-4">{{ __('admin/settings.add_title') }}</h2>

        <form action="{{ route('admin.settings.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">{{ __('admin/settings.key') }}:</label>
                <input type="text" name="key" class="w-full border px-4 py-2 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">{{ __('admin/settings.value') }}:</label>
                <input type="text" name="value" class="w-full border px-4 py-2 rounded-lg">
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" @click="openAddModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg">
                    {{ __('admin/settings.cancel') }}
                </button>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                    {{ __('admin/settings.add') }}
                </button>
            </div>
        </form>
    </div>
</div>
