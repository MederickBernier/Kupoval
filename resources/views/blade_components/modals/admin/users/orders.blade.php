<div x-show="openOrdersModal"
     x-cloak
     x-transition
     class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
     @click.away="openOrdersModal = false">
    <div class="bg-white p-6 rounded-lg shadow-lg w-[500px] relative">
        <!-- Bouton de fermeture -->
        <button @click="openOrdersModal = false" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800">
            <i class="bi bi-x-lg"></i>
        </button>

        <h2 class="text-xl font-semibold mb-4">{{ __('admin/users.user_orders') }}</h2>

        <template x-if="selectedUser && selectedUser.orders.length > 0">
            <div class="overflow-y-auto max-h-80">
                <table class="w-full border-collapse border border-gray-300">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left border">{{ __('admin/users.order_number') }}</th>
                            <th class="px-4 py-2 text-left border">{{ __('admin/users.order_date') }}</th>
                            <th class="px-4 py-2 text-left border">{{ __('admin/users.order_total') }}</th>
                            <th class="px-4 py-2 text-left border">{{ __('admin/users.order_status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="order in selectedUser.orders">
                            <tr class="border">
                                <td class="px-4 py-2 border" x-text="order.id"></td>
                                <td class="px-4 py-2 border" x-text="new Date(order.created_at).toLocaleDateString()"></td>
                                <td class="px-4 py-2 border" x-text="order.total + ' $'"></td>
                                <td class="px-4 py-2 border">
                                    <span class="px-2 py-1 text-sm rounded bg-gray-200 text-gray-800" x-text="order.status"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </template>

        <!-- Message si l'utilisateur n'a pas de commandes -->
        <template x-if="selectedUser && selectedUser.orders.length === 0">
            <p class="text-gray-500 bg-neutral p-3 rounded">{{ __('admin/users.no_orders_found') }}</p>
        </template>

        <div class="mt-4 flex justify-end">
            <button @click="openOrdersModal = false" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                {{ __('admin/users.close') }}
            </button>
        </div>
    </div>
</div>
