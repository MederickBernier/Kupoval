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
                    <th class="px-4 py-2 border">{{ __('admin/artists.email') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/artists.website') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/artists.social') }}</th>
                    <th class="px-4 py-2 border text-center">{{ __('admin/artists.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($artists as $artist)
                    <tr class="border">
                        <!-- Profile Picture -->
                        <td class="px-4 py-2 border text-center">
                            <img src="{{ asset($artist->photo) }}"
                                 alt="{{ $artist->name }}"
                                 class="w-16 h-16 object-cover rounded-lg shadow">
                        </td>

                        <!-- Name -->
                        <td class="px-4 py-2 border">{{ $artist->name }}</td>

                        <!-- Email -->
                        <td class="px-4 py-2 border">
                            @if($artist->email)
                                <a href="mailto:{{ $artist->email }}" class="text-blue-600 hover:underline">
                                    {{ $artist->email }}
                                </a>
                            @else
                                <span class="text-gray-500">—</span>
                            @endif
                        </td>

                        <!-- Website -->
                        <td class="px-4 py-2 border">
                            @if($artist->website)
                                <a href="{{ $artist->website }}" target="_blank" class="text-green-600 hover:underline">
                                    {{ __('admin/artists.view_website') }}
                                </a>
                            @else
                                <span class="text-gray-500">—</span>
                            @endif
                        </td>

                        <!-- Social Media -->
                        <td class="px-4 py-2 border text-center">
                            <div class="flex justify-center space-x-2">
                                @if($artist->facebook)
                                    <a href="{{ $artist->facebook }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        <i class="bi bi-facebook text-lg"></i>
                                    </a>
                                @endif
                                @if($artist->twitter)
                                    <a href="{{ $artist->twitter }}" target="_blank" class="text-blue-400 hover:text-blue-600">
                                        <i class="bi bi-twitter text-lg"></i>
                                    </a>
                                @endif
                                @if($artist->instagram)
                                    <a href="{{ $artist->instagram }}" target="_blank" class="text-pink-500 hover:text-pink-700">
                                        <i class="bi bi-instagram text-lg"></i>
                                    </a>
                                @endif
                                @if($artist->tiktok)
                                    <a href="{{ $artist->tiktok }}" target="_blank" class="text-black hover:text-gray-800">
                                        <i class="bi bi-tiktok text-lg"></i>
                                    </a>
                                @endif
                            </div>
                        </td>

                        <!-- Actions -->
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
