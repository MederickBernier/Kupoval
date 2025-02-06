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

        <h2 class="text-xl font-semibold mb-4">{{ __('admin/users.user_profile') }}</h2>
        <p><strong>{{ __('admin/users.username') }}:</strong> <span x-text="selectedUser?.username"></span></p>
        <p><strong>{{ __('admin/users.email') }}:</strong> <span x-text="selectedUser?.email"></span></p>
        <p><strong>{{ __('admin/users.role') }}:</strong> <span x-text="selectedUser?.role"></span></p>

        <hr class="my-4">

        <h3 class="text-lg font-semibold mb-2">{{ __('admin/users.profile_details') }}</h3>
        <p><strong>{{ __('admin/users.first_name') }}:</strong> <span x-text="selectedUser?.profile?.first_name"></span></p>
        <p><strong>{{ __('admin/users.last_name') }}:</strong> <span x-text="selectedUser?.profile?.last_name"></span></p>
        <p><strong>{{ __('admin/users.address') }}:</strong> <span x-text="selectedUser?.profile?.address"></span></p>
        <p><strong>{{ __('admin/users.city') }}:</strong> <span x-text="selectedUser?.profile?.city"></span></p>
        <p><strong>{{ __('admin/users.state') }}:</strong> <span x-text="selectedUser?.profile?.state"></span></p>
        <p><strong>{{ __('admin/users.country') }}:</strong> <span x-text="selectedUser?.profile?.country"></span></p>
        <p><strong>{{ __('admin/users.phone') }}:</strong> <span x-text="selectedUser?.profile?.phone"></span></p>

        <div class="mt-4 flex justify-end">
            <button @click="openProfileModal = false" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                {{ __('admin/users.close') }}
            </button>
        </div>
    </div>
</div>
