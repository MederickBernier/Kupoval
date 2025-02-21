<div class="flex items-center justify-between w-full">
    @if($isEditing)
        <select wire:model.defer="title"
                wire:keydown.enter="save"
                wire:blur="save"
                class="w-full border border-border px-3 py-2 rounded-lg focus:ring-accent focus:border-accent text-body">
            <option value="">{{ __('public/profile.select_title') }}</option>
            @foreach ($titles as $option)
                <option value="{{ $option }}">{{ $option }}</option>
            @endforeach
        </select>
    @else
        <span class="cursor-pointer text-body font-medium text-lg flex-grow" wire:click="edit">
            {{ $title ?: __('public/profile.select_title') }}
        </span>

        <!-- Pencil icon at the end of the line -->
        <i class="bi bi-pencil text-accent hover:text-cta cursor-pointer text-2xl font-bold drop-shadow-md ml-2"
           wire:click="edit"
           title="{{ __('Edit') }}"></i>
    @endif
</div>

@if (session()->has('message'))
    <p class="text-green-600 text-sm mt-1">{{ session('message') }}</p>
@endif

@if (session()->has('error'))
    <p class="text-red-600 text-sm mt-1">{{ session('error') }}</p>
@endif
