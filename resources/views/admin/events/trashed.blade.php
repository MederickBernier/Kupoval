@extends('layouts.admin')

@section('title', 'Trashed Events')

@section('page-title', 'Trashed Events')

@section('content')

<div x-data="restoreEventModal" class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">Deleted Events</h2>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Name</th>
                    <th class="px-4 py-2 border">Start Date</th>
                    <th class="px-4 py-2 border">End Date</th>
                    <th class="px-4 py-2 border">Deleted At</th>
                    <th class="px-4 py-2 border text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr class="border">
                        <td class="px-4 py-2 border">{{ $event->id }}</td>
                        <td class="px-4 py-2 border">{{ $event->name }}</td>
                        <td class="px-4 py-2 border">{{ $event->start_date }}</td>
                        <td class="px-4 py-2 border">{{ $event->end_date }}</td>
                        <td class="px-4 py-2 border">{{ $event->deleted_at->format('d M Y') }}</td>
                        <td class="px-4 py-2 border text-center">
                            <button @click="setRestoreEvent({{ $event->id }}, '{{ $event->name }}')"
                                    class="text-green-500 hover:underline">
                                <i class="bi bi-arrow-counterclockwise"></i> Restore
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $events->links() }}
    </div>

    <!-- Modale pour la restauration -->
    @include('blade_components.modals.admin.events.restore')

</div>

@endsection
