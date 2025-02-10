<div x-cloak x-show="openEditModal"
     x-transition
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 p-4"
     @keydown.window.escape="openEditModal = false"
     @click.away="openEditModal = false">

    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md sm:max-w-lg relative">
        <!-- Bouton de fermeture -->
        <button @click="openEditModal = false" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">
            <i class="bi bi-x-lg"></i>
        </button>

        <h2 class="text-xl font-bold text-blue-500 mb-4">{{ __('admin/events.edit_title') }}</h2>

        <form x-bind:action="'{{ url('/admin/events/') }}/' + selectedEvent.id" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" x-model="selectedEvent.id">

            <!-- Nom de l'événement -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">{{ __('admin/events.name') }}:</label>
                <input type="text" name="name" x-model="selectedEvent.name" class="w-full border px-4 py-2 rounded-lg" required>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">{{ __('admin/events.description') }}:</label>
                <textarea name="description" x-model="selectedEvent.description" class="editor w-full border px-4 py-2 rounded-lg h-24"></textarea>
            </div>

            <!-- Date de début -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">{{ __('admin/events.start_date') }}:</label>
                <input type="date" name="start_date" x-model="selectedEvent.start_date" class="w-full border px-4 py-2 rounded-lg" required>
            </div>

            <!-- Date de fin -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">{{ __('admin/events.end_date') }}:</label>
                <input type="date" name="end_date" x-model="selectedEvent.end_date" class="w-full border px-4 py-2 rounded-lg">
            </div>

            <!-- Lieu -->
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">{{ __('admin/events.location') }}:</label>
                <input type="text" name="location" x-model="selectedEvent.location" class="w-full border px-4 py-2 rounded-lg" required>
            </div>

            <!-- Boutons d'action -->
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                <button type="button" @click="openEditModal = false"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    {{ __('admin/events.cancel') }}
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    {{ __('admin/events.save_changes') }}
                </button>
            </div>
        </form>
    </div>
</div>
