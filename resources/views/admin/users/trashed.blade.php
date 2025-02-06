@extends('layouts.admin')

@section('title', __('admin/users.trashed_title'))

@section('page-title', __('admin/users.trashed_page_title'))

@section('content')

<div x-data="restoreUserModal()" class="bg-white p-4 sm:p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">{{ __('admin/users.trashed_list_title') }}</h2>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-2 sm:px-4 py-2 text-left border">{{ __('admin/users.id') }}</th>
                    <th class="px-2 sm:px-4 py-2 text-left border">{{ __('admin/users.username') }}</th>
                    <th class="px-2 sm:px-4 py-2 text-left border hidden md:table-cell">{{ __('admin/users.email') }}</th>
                    <th class="px-2 sm:px-4 py-2 text-left border">{{ __('admin/users.role') }}</th>
                    <th class="px-2 sm:px-4 py-2 text-left border hidden md:table-cell">{{ __('admin/users.deleted_at') }}</th>
                    <th class="px-2 sm:px-4 py-2 text-center border">{{ __('admin/users.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border">
                        <td class="px-2 sm:px-4 py-2 border">{{ $user->id }}</td>
                        <td class="px-2 sm:px-4 py-2 border">{{ $user->username }}</td>
                        <td class="px-2 sm:px-4 py-2 border hidden md:table-cell">{{ $user->email }}</td>
                        <td class="px-2 sm:px-4 py-2 border">{{ ucfirst($user->role) }}</td>
                        <td class="px-2 sm:px-4 py-2 border hidden md:table-cell">{{ $user->deleted_at->format('d M Y') }}</td>
                        <td class="px-2 sm:px-4 py-2 border text-center space-x-2">
                            <button @click="selectedUser = { id: {{ $user->id }}, username: '{{ $user->username }}' }; openRestoreModal = true"
                                    class="text-green-500 hover:underline">
                                <i class="bi bi-arrow-counterclockwise"></i> <span class="hidden sm:inline">{{ __('admin/users.restore') }}</span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <!-- Inclure la modale ici -->
    @include('blade_components.modals.admin.users.restore')

</div>

@endsection
