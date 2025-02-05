@extends('layouts.admin')

@section('title', 'Users List')

@section('page-title', 'Users List')

@section('content')

<div x-data="userManagement()" class="bg-white p-4 sm:p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">List of all Users</h2>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-2 sm:px-4 py-2 text-left border">ID</th>
                    <th class="px-2 sm:px-4 py-2 text-left border">Username</th>
                    <th class="px-2 sm:px-4 py-2 text-left border hidden md:table-cell">Email</th>
                    <th class="px-2 sm:px-4 py-2 text-left border">Role</th>
                    <th class="px-2 sm:px-4 py-2 text-left border hidden md:table-cell">Registered</th>
                    <th class="px-2 sm:px-4 py-2 text-center border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border">
                        <td class="px-2 sm:px-4 py-2 border">{{ $user->id }}</td>
                        <td class="px-2 sm:px-4 py-2 border">{{ $user->username }}</td>
                        <td class="px-2 sm:px-4 py-2 border hidden md:table-cell">{{ $user->email }}</td>
                        <td class="px-2 sm:px-4 py-2 border">{{ ucfirst($user->role) }}</td>
                        <td class="px-2 sm:px-4 py-2 border hidden md:table-cell">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-2 sm:px-4 py-2 border text-center space-x-2">
                            <button @click="selectUser({{ json_encode($user) }}); openProfileModal = true" class="text-blue-500 hover:underline">
                                <i class="bi bi-eye"></i> <span class="hidden sm:inline">View</span>
                            </button>

                            <button @click="selectUser({{ json_encode($user) }}); openOrdersModal = true" class="text-green-500 hover:underline">
                                <i class="bi bi-receipt"></i> <span class="hidden sm:inline">Orders</span>
                            </button>

                            <button @click="selectUser({{ json_encode($user) }}); openDeleteModal = true" class="text-red-500 hover:underline">
                                <i class="bi bi-trash"></i> <span class="hidden sm:inline">Delete</span>
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

    <!-- Modals -->
    @include('blade_components.modals.admin.users.profile')
    @include('blade_components.modals.admin.users.orders')
    @include('blade_components.modals.admin.users.delete')
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('userManagement', () => ({
        selectedUser: null,
        openProfileModal: false,
        openOrdersModal: false,
        openDeleteModal: false,
        confirmationText: '',

        selectUser(user) {
            this.selectedUser = user;
        },

        deleteUser() {
        if (!this.selectedUser) return;

        fetch(`{{ route('admin.users.destroy', '') }}/${this.selectedUser.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.openDeleteModal = false;
                alert(data.success);
                window.location.reload();
            } else {
                alert(data.error || 'Failed to delete user.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
        });
    }
    }));
});
</script>

@endsection
