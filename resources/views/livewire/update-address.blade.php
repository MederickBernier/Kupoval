<div class="relative border rounded-lg p-5 shadow-md bg-gray-50 hover:bg-gray-100 transition">
    @if($isEditing)
        <!-- Editing Mode -->
        <div class="space-y-3">
            <input type="text" wire:model.defer="address" class="w-full border px-3 py-2 rounded-lg focus:ring-accent focus:border-accent text-body" placeholder="Address">
            <input type="text" wire:model.defer="city" class="w-full border px-3 py-2 rounded-lg focus:ring-accent focus:border-accent text-body" placeholder="City">
            <input type="text" wire:model.defer="state" class="w-full border px-3 py-2 rounded-lg focus:ring-accent focus:border-accent text-body" placeholder="State">
            <input type="text" wire:model.defer="country" class="w-full border px-3 py-2 rounded-lg focus:ring-accent focus:border-accent text-body" placeholder="Country">
            <input type="text" wire:model.defer="zipcode" class="w-full border px-3 py-2 rounded-lg focus:ring-accent focus:border-accent text-body" placeholder="Zipcode">

            <div class="flex justify-end space-x-2 mt-2">
                <button wire:click="save" class="bg-accent text-white px-4 py-2 rounded-lg hover:bg-cta">Save</button>
                <button wire:click="$set('isEditing', false)" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Cancel</button>
            </div>
        </div>
    @else
        <!-- Display Mode -->
        <div class="space-y-1">
            <p class="text-lg font-semibold text-gray-900">{{ $address }}</p>
            <p class="text-gray-700">{{ $city }}, {{ $state }}</p>
            <p class="text-gray-500">{{ $country }}</p>
            <p class="text-gray-500">{{ $zipcode }}</p>
        </div>

        <!-- Single edit button per card -->
        <div class="absolute top-3 right-3">
            <i class="bi bi-pencil text-accent hover:text-cta cursor-pointer text-xl drop-shadow-md"
               wire:click="edit"
               title="Edit Address"></i>
        </div>
    @endif
</div>
