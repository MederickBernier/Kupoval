<div x-show="openProfileModal"
     x-cloak
     x-transition
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
     @click.away="openProfileModal = false">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 relative">
        <!-- Close Button -->
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
        <p><strong>{{ __('admin/users.phone') }}:</strong> <span x-text="selectedUser?.profile?.phone"></span></p>

        <hr class="my-4">

        <!-- 📌 Billing Address -->
        <h3 class="text-lg font-semibold mb-2">{{ __('admin/users.billing_address') }}</h3>
        <template x-if="selectedUser?.profile?.billing_address">
            <p class="text-gray-800">
                <span x-text="selectedUser?.profile?.billing_address?.address"></span>,
                <span x-text="selectedUser?.profile?.billing_address?.city"></span>,
                <span x-text="selectedUser?.profile?.billing_address?.state"></span>,
                <span x-text="selectedUser?.profile?.billing_address?.country"></span>
                <br>
                <span x-text="selectedUser?.profile?.billing_address?.zipcode"></span>
            </p>
        </template>
        <template x-if="!selectedUser?.profile?.billing_address">
            <p class="text-gray-500">{{ __('admin/users.no_billing_address') }}</p>
        </template>

        <hr class="my-4">

        <!-- 🚚 Shipping Addresses -->
        <h3 class="text-lg font-semibold mb-2">{{ __('admin/users.shipping_addresses') }}</h3>
        <template x-if="selectedUser?.profile?.shipping_addresses?.length > 0">
            <div>
                <template x-for="address in selectedUser?.profile?.shipping_addresses" :key="address.id">
                    <p class="text-gray-800">
                        <span x-text="address.address"></span>,
                        <span x-text="address.city"></span>,
                        <span x-text="address.state"></span>,
                        <span x-text="address.country"></span>
                        <br>
                        <span x-text="address.zipcode"></span>
                    </p>
                </template>
            </div>
        </template>
        <template x-if="!selectedUser?.profile?.shipping_addresses || selectedUser?.profile?.shipping_addresses?.length === 0">
            <p class="text-gray-500">{{ __('admin/users.no_shipping_addresses') }}</p>
        </template>

        <div class="mt-4 flex justify-end">
            <button @click="openProfileModal = false" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                {{ __('admin/users.close') }}
            </button>
        </div>
    </div>
</div>
