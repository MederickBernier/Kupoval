<div x-cloak x-show="openAddModal"
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md sm:max-w-lg">
        <h2 class="text-xl font-bold text-green-500 mb-4">Add New Setting</h2>

        <form action="#" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Key:</label>
                <input type="text" name="key" class="w-full border px-4 py-2 rounded-lg" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Value:</label>
                <input type="text" name="value" class="w-full border px-4 py-2 rounded-lg" required>
            </div>

            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                <button type="button" @click="openAddModal = false"
                        class="px-4 py-2 border rounded w-full sm:w-auto">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition w-full sm:w-auto">
                    Add Setting
                </button>
            </div>
        </form>
    </div>
</div>
