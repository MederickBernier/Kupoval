@extends('layouts.admin')

@section('title', __('admin/categories.index_title'))

@section('content')
<div x-data="categoryManagement()" class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">{{ __('admin/categories.list_categories') }}</h2>

    <!-- Bouton Ajouter une Catégorie -->
    <div class="flex justify-end mb-4">
        <button @click="openCreateModal = true"
                class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
            <i class="bi bi-plus"></i> {{ __('admin/categories.add_category') }}
        </button>
    </div>

    <!-- Table des Catégories -->
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
                            <button @click="selectCategory({{ json_encode($category) }}); openEditModal = true"
                                    class="text-blue-500 hover:underline">
                                <i class="bi bi-pencil"></i> {{ __('admin/categories.edit') }}
                            </button>
                            <button @click="selectCategory({{ json_encode($category) }}); openDeleteModal = true"
                                    class="text-red-500 hover:underline">
                                <i class="bi bi-trash"></i> {{ __('admin/categories.delete') }}
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

    <!-- Inclure les modales -->
    @include('blade_components.modals.admin.categories.create')
    @include('blade_components.modals.admin.categories.edit')
    @include('blade_components.modals.admin.categories.delete')

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('categoryManagement', () => ({
        selectedCategory: null,
        openCreateModal: false,
        openEditModal: false,
        openDeleteModal: false,

        selectCategory(category) {
            this.selectedCategory = category;
        }
    }));
});
</script>

@endsection
