<div x-cloak x-show="openCreateModal"
     x-transition
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4"
     @keydown.window.escape="openCreateModal = false"
     @click.away="openCreateModal = false">

    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md sm:max-w-lg relative">
        <!-- Bouton de fermeture -->
        <button @click="openCreateModal = false" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">
            <i class="bi bi-x-lg"></i>
        </button>

        <h2 class="text-xl font-bold text-green-500 mb-4">
            <i class="bi bi-folder-plus"></i> {{ __('admin/categories.create_title') }}
        </h2>

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <!-- Nom de la catégorie -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/categories.name') }}:</label>
                <input type="text" name="name" class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring focus:ring-green-300" required>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('admin/categories.description') }}:</label>
                <textarea name="description" class="editor w-full border border-gray-300 px-4 py-2 rounded-lg h-24 focus:ring focus:ring-green-300"></textarea>
            </div>

            <!-- Boutons d'action -->
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                <button type="button" @click="openCreateModal = false"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    <i class="bi bi-x-circle"></i> {{ __('public/interface.cancel') }}
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                    <i class="bi bi-check-circle"></i> {{ __('admin/categories.create_button') }}
                </button>
            </div>
        </form>
    </div>
</div>
