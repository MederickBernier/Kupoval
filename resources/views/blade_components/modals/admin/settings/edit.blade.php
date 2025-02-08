<div x-cloak x-show="openEditModal"
     class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold text-blue-600 mb-4">{{ __('admin/settings.edit_title') }}</h2>

        <form x-bind:action="selectedSetting && selectedSetting.id ? ('/admin/settings/edit/' + selectedSetting.id) : '#'"
              method="POST">
            @csrf
            @method('PUT')

            <div class="mt-4">
                <label for="settingValue" class="block text-sm font-medium text-gray-700">
                    {{ __('admin/settings.value_label') }}
                </label>
                <input type="text" id="settingValue"
                       x-model="selectedSetting ? selectedSetting.value : ''"
                       class="w-full border px-4 py-2 rounded-lg mt-2"
                       placeholder="{{ __('admin/settings.value_placeholder') }}">
            </div>

            <div class="flex justify-end space-x-2 mt-6">
                <button @click="openEditModal = false"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded">
                    {{ __('public/interface.cancel') }}
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    {{ __('admin/settings.save_button') }}
                </button>
            </div>
        </form>
    </div>
</div>
