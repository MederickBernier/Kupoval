<div x-cloak x-show="openDeleteModal"
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md sm:max-w-lg">
        <h2 class="text-xl font-bold text-red-500 mb-4">Confirm Setting Deletion</h2>
        <p class="mb-4 text-sm sm:text-base">
            You are about to delete the setting
            <strong class="text-red-600" x-text="selectedSetting.key"></strong>.
            This action is <strong>irreversible</strong>.
            To confirm, please type the setting key below.
        </p>

        <form action="#" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="id" x-model="selectedSetting.id">

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Enter Key:</label>
                <input type="text"
                       x-model="confirmationInput"
                       class="w-full border px-4 py-2 rounded-lg text-sm sm:text-base"
                       placeholder="Type setting key here..."
                       required>
            </div>

            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                <button type="button" @click="openDeleteModal = false"
                        class="px-4 py-2 border rounded w-full sm:w-auto">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition w-full sm:w-auto"
                        :disabled="confirmationInput !== selectedSetting.key">
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>
