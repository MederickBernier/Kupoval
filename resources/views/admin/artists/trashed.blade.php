@extends('layouts.admin')

@section('title', __('admin/artists.trashed_title'))

@section('content')

<div x-data="artistManager()" class="bg-white p-6 rounded-lg shadow-lg">
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">{{ __('admin/artists.trashed_heading') }}</h2>
        <a href="{{ route('admin.artists.index') }}"
           class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            ← {{ __('admin/artists.back_to_list') }}
        </a>
    </div>

    <!-- Artists Table -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm sm:text-base">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">{{ __('admin/artists.photo') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/artists.name') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/artists.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($artists as $artist)
                    <tr class="border">
                        <td class="px-4 py-2 border text-center">
                            <img src="{{ asset($artist->photo) }}"
                                 alt="{{ $artist->name }}"
                                 class="w-16 h-16 object-cover rounded-lg shadow">
                        </td>
                        <td class="px-4 py-2 border">{{ $artist->name }}</td>
                        <td class="px-4 py-2 border text-center space-x-2">
                            <!-- Restore Button -->
                            <button @click="setRestoreArtist({{ json_encode($artist) }}, '{{ route('admin.artists.restore', $artist->id) }}')"
                                    class="text-green-500 hover:underline">
                                <i class="bi bi-arrow-clockwise"></i> {{ __('admin/artists.restore') }}
                            </button>

                            <!-- Force Delete Button -->
                            <button @click="setForceDeleteArtist({{ json_encode($artist) }}, '{{ route('admin.artists.forceDelete', $artist->id) }}')"
                                    class="text-red-500 hover:underline">
                                <i class="bi bi-trash"></i> {{ __('admin/artists.force_delete') }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $artists->links() }}
    </div>

    <!-- Include Modals -->
    @include('blade_components.modals.admin.artists.force_delete')
    @include('blade_components.modals.admin.artists.restore')
</div>

<!-- Alpine.js Component for Artists -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('artistManager', () => ({
        // Restore Modal
        openRestoreArtistModal: false,
        selectedArtist: { id: '', name: '', slug: '', restoreUrl: '' },

        setRestoreArtist(artist, url) {
            this.selectedArtist = { ...artist, restoreUrl: url };
            this.openRestoreArtistModal = true;
        },

        restoreArtist() {
            if (!this.selectedArtist.slug) {
                console.error("No artist selected for restore.");
                return;
            }

            fetch(this.selectedArtist.restoreUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to restore artist.');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    this.openRestoreArtistModal = false;
                    alert(data.success);
                    window.location.reload();
                } else {
                    alert(data.error || "An error occurred while restoring the artist.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Unexpected error occurred.");
            });
        },

        // Force Delete Modal
        openForceDeleteArtistModal: false,
        selectedArtist: { id: '', name: '', slug: '', deleteUrl: '' },

        setForceDeleteArtist(artist, url) {
            this.selectedArtist = { ...artist, deleteUrl: url };
            this.openForceDeleteArtistModal = true;
        },

        forceDeleteArtist() {
            if (!this.selectedArtist.slug) {
                console.error("No artist selected for force deletion.");
                return;
            }

            fetch(this.selectedArtist.deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to delete artist.');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    this.openForceDeleteArtistModal = false;
                    alert(data.success);
                    window.location.reload();
                } else {
                    alert(data.error || "An error occurred while deleting the artist.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Unexpected error occurred.");
            });
        }
    }));
});
</script>

@endsection
