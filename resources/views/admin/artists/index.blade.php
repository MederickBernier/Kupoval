@extends('layouts.admin')

@section('title', __('admin/artists.list_title'))

@section('content')

<div x-data="artistManager()" class="bg-white p-6 rounded-lg shadow-lg">
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">{{ __('admin/artists.list_heading') }}</h2>
        <a href="{{ route('admin.artists.create') }}"
           class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
            + {{ __('admin/artists.add_new') }}
        </a>
    </div>

    <!-- Artists Table -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm sm:text-base">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">{{ __('admin/artists.photo') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/artists.name') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/artists.bio') }}</th>
                    <th class="px-4 py-2 border text-center">{{ __('admin/artists.actions') }}</th>
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
                        <td class="px-4 py-2 border">{{ Str::limit(strip_tags($artist->bio), 50) }}</td>
                        <td class="px-4 py-2 border text-center space-x-2">
                            <!-- View Button -->
                            <button @click="setShowArtist({{ json_encode($artist) }}, '{{ route('admin.artists.edit', $artist->slug) }}')"
                                    class="text-blue-500 hover:underline">
                                <i class="bi bi-eye"></i> {{ __('admin/artists.view') }}
                            </button>

                            <!-- Edit Button -->
                            <a href="{{ route('admin.artists.edit', $artist->slug) }}"
                               class="text-yellow-500 hover:underline">
                                <i class="bi bi-pencil"></i> {{ __('admin/artists.edit') }}
                            </a>

                            <!-- Delete Button -->
                            <button @click="setDeleteArtist({{ json_encode($artist) }}, '{{ route('admin.artists.destroy', $artist->slug) }}')"
                                    class="text-red-500 hover:underline">
                                <i class="bi bi-trash"></i> {{ __('admin/artists.delete') }}
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
    @include('blade_components.modals.admin.artists.show')
    @include('blade_components.modals.admin.artists.delete')
</div>

<!-- Alpine.js Component for Artists -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('artistManager', () => ({
        // Show Modal
        openShowArtistModal: false,
        selectedArtist: { id: '', name: '', bio: '', photo: '', editUrl: '' },

        setShowArtist(artist, editUrl) {
            this.selectedArtist = { ...artist, editUrl };
            this.openShowArtistModal = true;
        },

        // Delete Modal
        openDeleteArtistModal: false,
        deleteUrl: '',
        deleteConfirmation: '',

        setDeleteArtist(artist, url) {
            this.selectedArtist = artist;
            this.deleteUrl = url;
            this.deleteConfirmation = '';
            this.openDeleteArtistModal = true;
        }
    }));
});
</script>

@endsection
