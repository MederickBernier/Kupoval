<div x-cloak x-show="openAddModal"
     x-transition
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4"
     @keydown.window.escape="openAddModal = false"
     @click.away="openAddModal = false">

    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md sm:max-w-lg relative">
        <!-- Bouton de fermeture -->
        <button @click="openAddModal = false" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">
            <i class="bi bi-x-lg"></i>
        </button>

        <h2 class="text-xl font-bold text-green-500 mb-4">Add New Event</h2>

        <form action="{{ route('admin.events.store') }}" method="POST">
            @csrf

            <!-- Nom de l'événement -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Name:</label>
                <input type="text" name="name" class="w-full border px-4 py-2 rounded-lg" required>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Description:</label>
                <textarea name="description" class="w-full border px-4 py-2 rounded-lg h-24"></textarea>
            </div>

            <!-- Date de début -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Start Date:</label>
                <input type="date" name="start_date" class="w-full border px-4 py-2 rounded-lg" required>
            </div>

            <!-- Date de fin -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">End Date:</label>
                <input type="date" name="end_date" class="w-full border px-4 py-2 rounded-lg">
            </div>

            <!-- Lieu -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Location:</label>
                <input type="text" name="location" class="w-full border px-4 py-2 rounded-lg" required>
            </div>

            <!-- Boutons d'action -->
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                <button type="button" @click="openAddModal = false"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                    Add Event
                </button>
            </div>
        </form>
    </div>
</div>
