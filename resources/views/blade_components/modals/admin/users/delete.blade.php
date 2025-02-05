<div x-cloak x-show="openDeleteModal"
     x-transition.opacity
     class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
     @keydown.window.escape="openDeleteModal = false"
     @click.away="openDeleteModal = false">

    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-semibold text-red-600 mb-4">Confirm User Deletion</h2>
        <p>
            You are about to delete <strong x-text="selectedUser ? selectedUser.username : 'Unknown'"></strong>.
            This action is <strong>irreversible</strong>.
        </p>

        <!-- Confirmation Input -->
        <div class="mt-4">
            <label for="confirmName" class="font-semibold">Enter Full Name:</label>
            <input type="text" id="confirmName" class="w-full p-2 border rounded mt-1"
                   placeholder="Type full name here..." x-model="confirmationText">
        </div>

        <div class="flex justify-end space-x-2 mt-6">
            <button @click="openDeleteModal = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">Cancel</button>
            <button
                :disabled="confirmationText !== (selectedUser ? selectedUser.username : '')"
                class="px-4 py-2 bg-red-500 text-white rounded disabled:opacity-50 disabled:cursor-not-allowed">
                Confirm Delete
            </button>
        </div>
    </div>
</div>
