@extends('layouts.admin')

@section('title', __('admin/categories.trashed_title'))

@section('content')

<div x-data="categoryManagement()" class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">{{ __('admin/categories.trashed_list') }}</h2>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">{{ __('admin/categories.name') }}</th>
                    <th class="px-4 py-2 border hidden md:table-cell">{{ __('admin/categories.description') }}</th>
                    <th class="px-4 py-2 border text-center">{{ __('admin/categories.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr class="border">
                        <td class="px-4 py-2 border">{{ $category->name }}</td>
                        <td class="px-4 py-2 border hidden md:table-cell">{{ $category->description }}</td>
                        <td class="px-4 py-2 border text-center space-x-2">
                            <button @click="selectCategory({{ json_encode($category) }}); openRestoreModal = true"
                                    class="text-green-500 hover:underline">
                                <i class="bi bi-arrow-counterclockwise"></i> {{ __('admin/categories.restore') }}
                            </button>

                            <button @click="selectCategory({{ json_encode($category) }}); openForceDeleteModal = true"
                                    class="text-red-500 hover:underline">
                                <i class="bi bi-trash"></i> {{ __('admin/categories.force_delete') }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>

    @include('blade_components.modals.admin.categories.restore')
    @include('blade_components.modals.admin.categories.force_delete')

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('categoryManagement', () => ({
        selectedCategory: null,
        openRestoreModal: false,
        openForceDeleteModal: false,

        selectCategory(category) {
            this.selectedCategory = category;
        }
    }));
});
</script>

@endsection
