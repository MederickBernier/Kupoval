@extends('layouts.admin')

@section('title', 'Settings List')

@section('page-title', 'Settings List')

@section('content')

<div x-data="{
    openAddModal: false,
    openEditModal: false,
    openDeleteModal: false,
    selectedSetting: null,
    confirmationInput: ''
}" class="bg-white p-6 rounded-lg shadow-lg">

    <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-4 sm:space-y-0">
        <h2 class="text-xl font-semibold">List of Settings</h2>
        <button @click="openAddModal = true" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 w-full sm:w-auto">
            + Add New Setting
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm sm:text-base">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left border">Key</th>
                    <th class="px-4 py-2 text-left border">Value</th>
                    <th class="px-4 py-2 text-center border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($settings as $setting)
                    <tr class="border">
                        <td class="px-4 py-2 border truncate max-w-[150px] sm:max-w-full">{{ $setting->key }}</td>
                        <td class="px-4 py-2 border truncate max-w-[200px] sm:max-w-full">{{ $setting->value }}</td>
                        <td class="px-4 py-2 border text-center space-x-2 flex justify-center">
                            <button @click="openEditModal = true; selectedSetting = {{ json_encode($setting) }}"
                                class="text-blue-500 hover:underline flex items-center">
                                <i class="bi bi-pencil mr-1"></i> Edit
                            </button>
                            <button @click="openDeleteModal = true; selectedSetting = {{ json_encode($setting) }}; confirmationInput = ''"
                                class="text-red-500 hover:underline flex items-center">
                                <i class="bi bi-trash mr-1"></i> Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 flex justify-center sm:justify-end">
        {{ $settings->links() }}
    </div>

    <!-- Modals -->
    @include('blade_components.modals.admin.settings.add')
    @include('blade_components.modals.admin.settings.edit')
    @include('blade_components.modals.admin.settings.delete')

</div>

@endsection
