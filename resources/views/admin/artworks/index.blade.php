@extends('layouts.admin')

@section('title', __('admin/artworks.list_title'))

@section('page-title', __('admin/artworks.list_title'))

@section('content')

<div x-data="artworkManager()" class="bg-white p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">{{ __('admin/artworks.list_heading') }}</h2>
        <a href="{{ route('admin.artworks.create') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
            + {{ __('admin/artworks.add_new') }}
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm sm:text-base">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">{{ __('admin/artworks.image') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/artworks.name') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/artworks.artist') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/artworks.price') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/artworks.for_sale') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/artworks.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($artworks as $artwork)
                    <tr class="border">
                        <td class="px-4 py-2 border text-center">
                            <img src="{{ asset('storage/artworks/' . basename($artwork->image)) }}"
                                 alt="{{ $artwork->name }}"
                                 class="w-16 h-16 object-cover rounded-lg shadow">
                        </td>
                        <td class="px-4 py-2 border">{{ $artwork->name }}</td>
                        <td class="px-4 py-2 border">{{ $artwork->artist->name ?? __('admin/artworks.unknown_artist') }}</td>
                        <td class="px-4 py-2 border">${{ number_format($artwork->initial_price, 2) }}</td>
                        <td class="px-4 py-2 border text-center">
                            @if($artwork->is_on_sale)
                                <span class="text-green-500 font-semibold">{{ __('admin/artworks.yes') }}</span>
                            @else
                                <span class="text-red-500 font-semibold">{{ __('admin/artworks.no') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 border text-center space-x-2">
                            <a href="{{ route('admin.artworks.edit', $artwork->slug) }}" class="text-blue-500 hover:underline">
                                <i class="bi bi-pencil"></i> {{ __('admin/artworks.edit') }}
                            </a>
                            <button @click="setDeleteArtwork({{ json_encode($artwork) }}, '{{ route('admin.artworks.destroy', $artwork->slug) }}')"
                                    class="text-red-500 hover:underline">
                                <i class="bi bi-trash"></i> {{ __('admin/artworks.delete') }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $artworks->links() }}
    </div>

    @include('blade_components.modals.admin.artworks.delete')
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('artworkManager', () => ({
        openDeleteArtworkModal: false,
        selectedArtwork: { id: '', name: '', description: '', image: '' },
        deleteUrl: '',
        deleteConfirmation: '',

        setDeleteArtwork(artwork, url) {
            this.selectedArtwork = artwork;
            this.deleteUrl = url;
            this.deleteConfirmation = '';
            this.openDeleteArtworkModal = true;
        }
    }));
});
</script>

@endsection
