<div x-cloak x-show="openDeleteModal"
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md sm:max-w-lg">
        <h2 class="text-xl font-bold text-red-500 mb-4">{{ __('admin/settings.delete_title') }}</h2>
        <p>{{ __('admin/settings.delete_confirmation') }} <strong x-text="selectedSetting.key"></strong>. {{ __('admin/settings.irreversible_action') }}</p>

        <form :action="`/admin/settings/${selectedSetting.id}`" method="POST">
            @csrf
            @method('DELETE')

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" @click="openDeleteModal = false" class="px-4 py-2 border rounded">
                    {{ __('admin/settings.cancel') }}
                </button>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                    {{ __('admin/settings.delete') }}
                </button>
            </div>
        </form>
    </div>
</div>
