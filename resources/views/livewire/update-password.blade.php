<div>
    <!-- ✅ Button to open modal -->
    <button wire:click="openModal"
            class="text-link font-medium hover:underline focus:outline-none">
        {{ __('public/profile.edit_password') }}
    </button>

    <!-- ✅ Password update modal -->
    @if($showModal)
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <!-- ✅ Background overlay -->
            <div class="absolute inset-0 bg-gray-900 opacity-50" wire:click="closeModal"></div>

            <!-- ✅ Modal content -->
            <div class="bg-white p-6 rounded-lg shadow-emerald z-50 max-w-md w-full">
                <h2 class="text-xl font-title font-semibold text-heading mb-4">
                    {{ __('public/profile.change_password') }}
                </h2>

                <!-- ✅ Success message -->
                @if (session()->has('success'))
                    <div class="bg-green-100 text-green-700 p-2 rounded mb-2 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- ✅ Password update form -->
                <form wire:submit.prevent="updatePassword" class="space-y-4">
                    <!-- 🔐 Current password -->
                    <div>
                        <label class="block text-body font-medium">
                            {{ __('public/profile.current_password') }}
                        </label>
                        <input type="password"
                               wire:model="current_password"
                               class="w-full border border-border rounded-lg px-3 py-2 focus:ring-accent focus:border-accent">
                        @error('current_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- 🔑 New password -->
                    <div>
                        <label class="block text-body font-medium">
                            {{ __('public/profile.new_password') }}
                        </label>
                        <input type="password"
                               wire:model="new_password"
                               class="w-full border border-border rounded-lg px-3 py-2 focus:ring-accent focus:border-accent">
                        @error('new_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- 🔐 Confirm new password -->
                    <div>
                        <label class="block text-body font-medium">
                            {{ __('public/profile.confirm_new_password') }}
                        </label>
                        <input type="password"
                               wire:model="new_password_confirmation"
                               class="w-full border border-border rounded-lg px-3 py-2 focus:ring-accent focus:border-accent">
                    </div>

                    <!-- ✅ Buttons -->
                    <div class="flex justify-end space-x-2">
                        <button type="button" wire:click="closeModal"
                                class="px-4 py-2 bg-gray-300 text-gray-800 font-medium rounded-lg hover:bg-gray-400 transition">
                            {{ __('public/profile.cancel') }}
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-cta text-white font-medium rounded-lg hover:bg-orange-600 transition">
                            {{ __('public/profile.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
