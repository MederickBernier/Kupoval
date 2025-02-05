<div x-cloak x-show="openEditModal"
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md sm:max-w-lg">
        <h2 class="text-xl font-bold text-blue-500 mb-4">Edit Setting</h2>

        <form action="#" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" x-model="selectedSetting.id">

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Key:</label>
                <input type="text" x-model="selectedSetting.key"
                       class="w-full border px-4 py-2 rounded-lg bg-gray-100" readonly>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Value:</label>
                <input type="text" name="value" x-model="selectedSetting.value"
                       class="w-full border px-4 py-2 rounded-lg">
            </div>

            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                <button type="button" @click="openEditModal = false"
                        class="px-4 py-2 border rounded w-full sm:w-auto">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition w-full sm:w-auto">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
