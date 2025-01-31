<div class="flex items-center justify-between w-full">
    @if($isEditing)
        <input
            type="text"
            wire:model.defer="value"
            wire:keydown.enter="save"
            wire:blur="save"
            class="w-full border border-border px-3 py-2 rounded-lg focus:ring-accent focus:border-accent text-body"
        >
    @else
        <span class="cursor-pointer text-body font-medium text-lg flex-grow" wire:click="edit">
            {{ $value ?: '-' }}
        </span>

        <!-- Icône bien alignée à droite -->
        <i class="bi bi-pencil text-accent hover:text-cta cursor-pointer text-2xl font-bold drop-shadow-md ml-2"
           wire:click="edit"
           title="Edit"></i>
    @endif
</div>
