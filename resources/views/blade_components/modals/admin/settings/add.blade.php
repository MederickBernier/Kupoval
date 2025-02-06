<div x-cloak x-show="openAddModal"
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md sm:max-w-lg">
        <h2 class="text-xl font-bold text-green-500 mb-4">{{ __('admin/settings.add_title') }}</h2>

        <!-- Message d'avertissement -->
        <div class="bg-yellow-100 text-yellow-700 p-3 rounded-lg mb-4">
            <p class="text-sm">
                ⚠️ {{ __('admin/settings.warning_message') }}
            </p>
        </div>

        <form action="{{ route('admin.settings.store') }}" method="POST">
            @csrf

            <!-- Key -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">{{ __('admin/settings.key') }}:</label>
                <input type="text" name="key" class="w-full border px-4 py-2 rounded-lg" required>
            </div>

            <!-- Value -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">{{ __('admin/settings.value') }}:</label>
                <input type="text" name="value" class="w-full border px-4 py-2 rounded-lg" required>
            </div>

            <!-- Boutons -->
            <div class="flex justify-end space-x-2">
                <button type="button" @click="openAddModal = false" class="px-4 py-2 border rounded">
                    {{ __('admin/settings.cancel') }}
                </button>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                    {{ __('admin/settings.add') }}
                </button>
            </div>
        </form>
    </div>
</div>
