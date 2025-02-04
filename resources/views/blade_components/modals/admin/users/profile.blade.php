<div x-show="openProfileModal"
     x-cloak
     x-transition
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
     @click.away="openProfileModal = false">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative">
        <!-- Bouton de fermeture -->
        <button @click="openProfileModal = false" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">
            <i class="bi bi-x-lg"></i>
        </button>

        <h2 class="text-xl font-semibold mb-4">User Profile</h2>
        <p><strong>Username:</strong> <span x-text="selectedUser?.username"></span></p>
        <p><strong>Email:</strong> <span x-text="selectedUser?.email"></span></p>
        <p><strong>Role:</strong> <span x-text="selectedUser?.role"></span></p>

        <hr class="my-4">

        <h3 class="text-lg font-semibold mb-2">Profile Details</h3>
        <p><strong>First Name:</strong> <span x-text="selectedUser?.profile?.first_name"></span></p>
        <p><strong>Last Name:</strong> <span x-text="selectedUser?.profile?.last_name"></span></p>
        <p><strong>Address:</strong> <span x-text="selectedUser?.profile?.address"></span></p>
        <p><strong>City:</strong> <span x-text="selectedUser?.profile?.city"></span></p>
        <p><strong>State:</strong> <span x-text="selectedUser?.profile?.state"></span></p>
        <p><strong>Country:</strong> <span x-text="selectedUser?.profile?.country"></span></p>
        <p><strong>Phone:</strong> <span x-text="selectedUser?.profile?.phone"></span></p>

        <div class="mt-4 flex justify-end">
            <button @click="openProfileModal = false" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Close</button>
        </div>
    </div>
</div>
