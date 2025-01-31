<div>
    <!-- ✅ Bouton pour ouvrir la modale -->
    <button wire:click="openModal"
            class="text-link font-medium hover:underline focus:outline-none">
        Edit Password
    </button>

    <!-- ✅ Modale de modification du mot de passe -->
    @if($showModal)
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <!-- ✅ Fond d'arrière-plan -->
            <div class="absolute inset-0 bg-gray-900 opacity-50" wire:click="closeModal"></div>

            <!-- ✅ Contenu de la modale -->
            <div class="bg-white p-6 rounded-lg shadow-emerald z-50 max-w-md w-full">
                <h2 class="text-xl font-title font-semibold text-heading mb-4">Change Password</h2>

                <!-- ✅ Message de succès -->
                @if (session()->has('success'))
                    <div class="bg-green-100 text-green-700 p-2 rounded mb-2 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- ✅ Formulaire de modification du mot de passe -->
                <form wire:submit.prevent="updatePassword" class="space-y-4">
                    <!-- 🔐 Ancien mot de passe -->
                    <div>
                        <label class="block text-body font-medium">Current Password</label>
                        <input type="password"
                               wire:model="current_password"
                               class="w-full border border-border rounded-lg px-3 py-2 focus:ring-accent focus:border-accent">
                        @error('current_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- 🔑 Nouveau mot de passe -->
                    <div>
                        <label class="block text-body font-medium">New Password</label>
                        <input type="password"
                               wire:model="new_password"
                               class="w-full border border-border rounded-lg px-3 py-2 focus:ring-accent focus:border-accent">
                        @error('new_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- 🔐 Confirmation du mot de passe -->
                    <div>
                        <label class="block text-body font-medium">Confirm New Password</label>
                        <input type="password"
                               wire:model="new_password_confirmation"
                               class="w-full border border-border rounded-lg px-3 py-2 focus:ring-accent focus:border-accent">
                    </div>

                    <!-- ✅ Boutons -->
                    <div class="flex justify-end space-x-2">
                        <button type="button" wire:click="closeModal"
                                class="px-4 py-2 bg-gray-300 text-gray-800 font-medium rounded-lg hover:bg-gray-400 transition">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-cta text-white font-medium rounded-lg hover:bg-orange-600 transition">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
