@extends('layouts.admin')

@section('title', __('admin/events.trashed_title'))

@section('page-title', __('admin/events.trashed_title'))

@section('content')

<div x-data="restoreEventModal" class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">{{ __('admin/events.deleted_events') }}</h2>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">{{ __('admin/events.id') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/events.name') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/events.start_date') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/events.end_date') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/events.deleted_at') }}</th>
                    <th class="px-4 py-2 border text-center">{{ __('admin/events.actions') }}</th>
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
                                <i class="bi bi-arrow-counterclockwise"></i> {{ __('admin/events.restore') }}
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
