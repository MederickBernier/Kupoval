@extends('layouts.admin')

@section('title', __('admin/events.list_title'))

@section('page-title', __('admin/events.list_title'))

@section('content')

<div x-data="eventManager()" class="bg-white p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">{{ __('admin/events.list_heading') }}</h2>
        <button @click="openAddModal = true" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
            + {{ __('admin/events.add_new') }}
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm sm:text-base">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">{{ __('admin/events.name') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/events.start_date') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/events.end_date') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/events.location') }}</th>
                    <th class="px-4 py-2 border text-center">{{ __('admin/events.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr class="border">
                        <td class="px-4 py-2 border">{{ $event->name }}</td>
                        <td class="px-4 py-2 border">{{ $event->start_date }}</td>
                        <td class="px-4 py-2 border">{{ $event->end_date }}</td>
                        <td class="px-4 py-2 border">{{ $event->location }}</td>
                        <td class="px-4 py-2 border text-center space-x-2">
                            <button @click="setSelectedEvent({{ json_encode($event) }}); openEditModal = true"
                                    class="text-blue-500 hover:underline">
                                <i class="bi bi-pencil"></i> {{ __('admin/events.edit') }}
                            </button>
                            <button @click="setSelectedEvent({{ json_encode($event) }}); openDeleteModal = true"
                                    class="text-red-500 hover:underline">
                                <i class="bi bi-trash"></i> {{ __('admin/events.delete') }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $events->links() }}
    </div>

    @include('blade_components.modals.admin.events.add')
    @include('blade_components.modals.admin.events.edit')
    @include('blade_components.modals.admin.events.delete')
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('eventManager', () => ({
        openAddModal: false,
        openEditModal: false,
        openDeleteModal: false,
        selectedEvent: {
            id: '',
            name: '',
            description: '',
            start_date: '',
            end_date: '',
            location: ''
        },

        setSelectedEvent(event) {
            this.selectedEvent = event || {
                id: '',
                name: '',
                description: '',
                start_date: '',
                end_date: '',
                location: ''
            };
        },

        async deleteEvent() {
            if (!this.selectedEvent.id) {
                console.error("{{ __('admin/events.error_no_selection') }}");
                alert("{{ __('admin/events.error_no_selection') }}");
                return;
            }

            let url = `/admin/events/${this.selectedEvent.id}`;

            try {
                let response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                });

                let data = await response.json();

                if (response.ok) {
                    alert(data.success);
                    this.openDeleteModal = false;
                    window.location.reload();
                } else {
                    alert(data.error || "{{ __('admin/events.delete_failed') }}");
                }
            } catch (error) {
                console.error('Error:', error);
                alert("{{ __('admin/events.unexpected_error') }}");
            }
        }
    }));
});
</script>

@endsection
