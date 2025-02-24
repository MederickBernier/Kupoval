@extends('layouts.admin')

@section('title', __('admin/settings.list_title'))

@section('page-title', __('admin/settings.list_title'))

@section('content')

<!-- Alpine.js Component -->
<div x-data="settingsManager()" class="bg-white p-6 rounded-lg shadow-lg">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">{{ __('admin/settings.list_heading') }}</h2>
        <button @click="openAddModal = true" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
            + {{ __('admin/settings.add_new') }}
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm sm:text-base">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">{{ __('admin/settings.key') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/settings.value') }}</th>
                    <th class="px-4 py-2 border text-center">{{ __('admin/settings.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($settings as $setting)
                    <tr class="border">
                        <td class="px-4 py-2 border">{{ $setting->key }}</td>
                        <td class="px-4 py-2 border">{{ $setting->value ?? '' }}</td>
                        <td class="px-4 py-2 border text-center">
                            <button @click="editSetting({{ json_encode($setting) }})"
                                    class="text-blue-500 hover:underline">
                                <i class="bi bi-pencil"></i> {{ __('admin/settings.edit') }}
                            </button>
                            <button @click="deleteSetting({{ json_encode($setting) }})"
                                    class="text-red-500 hover:underline">
                                <i class="bi bi-trash"></i> {{ __('admin/settings.delete') }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $settings->links() }}
    </div>

    @include('blade_components.modals.admin.settings.add')
    @include('blade_components.modals.admin.settings.edit')
    @include('blade_components.modals.admin.settings.delete')

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('settingsManager', () => ({
            openAddModal: false,
            openEditModal: false,
            openDeleteModal: false,
            selectedSetting: { id: '', key: '', value: '' },

            editSetting(setting) {
                this.selectedSetting = setting;
                this.openEditModal = true;
            },

            deleteSetting(setting) {
                this.selectedSetting = setting;
                this.openDeleteModal = true;
            },

            async confirmDelete() {
                if (!this.selectedSetting.id) {
                    alert("{{ __('admin/settings.error_no_selection') }}");
                    return;
                }

                let url = `/admin/settings/${this.selectedSetting.id}`;

                try {
                    let response = await fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                    });

                    let data = await response.json();

                    if (response.ok) {
                        alert(data.success);
                        this.openDeleteModal = false;
                        window.location.reload();
                    } else {
                        alert(data.error || "{{ __('admin/settings.delete_failed') }}");
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert("{{ __('admin/settings.unexpected_error') }}");
                }
            }
        }));
    });
    </script>
@endsection
