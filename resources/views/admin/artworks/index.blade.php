@extends('layouts.admin')

@section('title', 'Artworks List')

@section('page-title', 'Artworks List')

@section('content')

<div x-data="artworkManager()" class="bg-white p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">List of Artworks</h2>
        <a href="{{ route('admin.artworks.create') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
            + Add Artwork
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm sm:text-base">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">Image</th>
                    <th class="px-4 py-2 border">Name</th>
                    <th class="px-4 py-2 border">Artist</th>
                    <th class="px-4 py-2 border">Price</th>
                    <th class="px-4 py-2 border">For Sale</th>
                    <th class="px-4 py-2 border">Actions</th>
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
                        <td class="px-4 py-2 border">{{ $artwork->artist->name ?? 'Unknown' }}</td>
                        <td class="px-4 py-2 border">${{ number_format($artwork->initial_price, 2) }}</td>
                        <td class="px-4 py-2 border text-center">
                            @if($artwork->is_on_sale)
                                <span class="text-green-500 font-semibold">Yes</span>
                            @else
                                <span class="text-red-500 font-semibold">No</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 border text-center space-x-2">
                            <a href="{{ route('admin.artworks.edit', $artwork->id) }}" class="text-blue-500 hover:underline">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <button @click="setDeleteArtwork({{ json_encode($artwork) }}, '{{ route('admin.artworks.destroy', $artwork->id) }}')"
                                    class="text-red-500 hover:underline">
                                <i class="bi bi-trash"></i> Delete
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
