<div>
    @if($isEditing)
        <input
            type="text"
            wire:model.defer="value"
            wire:keydown.enter="save"
            wire:blur="save"
            class="border px-2 py-1 rounded w-full"
        >
    @else
        <span class="cursor-pointer" wire:click="edit">{{ $value ?: '-' }}</span>
        <i class="bi bi-pencil text-gray-500 hover:text-gray-700 cursor-pointer" wire:click="edit"></i>
    @endif
</div>
