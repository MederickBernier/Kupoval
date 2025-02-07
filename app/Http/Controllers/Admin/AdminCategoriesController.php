<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Validation\Rule;

class AdminCategoriesController extends Controller
{
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());

            // Ajout de pagination pour optimiser l'affichage
            $categories = Category::paginate(10);
            return view('admin.categories.index', compact('categories'));
        } catch (\Exception $e) {
            throwError(__('Error loading categories list'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            isAllowed($request->user());

            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'description' => 'nullable|string',
            ]);

            Category::create($request->only(['name', 'description']));

            return redirect()->route('admin.categories.index')
                ->with('success', __('Category created successfully.'));
        } catch (\Exception $e) {
            throwError(__('Error creating category'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function edit(Request $request, Category $category)
    {
        try {
            isAllowed($request->user());

            return view('admin.categories.edit', compact('category'));
        } catch (\Exception $e) {
            throwError(__('Error loading category edit page'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Category $category)
    {
        try {
            isAllowed($request->user());

            $request->validate([
                'name' => ['required', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
                'description' => 'nullable|string',
            ]);

            $category->update($request->only(['name', 'description']));

            return redirect()->route('admin.categories.index')
                ->with('success', __('Category updated successfully.'));
        } catch (\Exception $e) {
            throwError(__('Error updating category'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, Category $category)
    {
        try {
            isAllowed($request->user());

            $category->delete();

            return redirect()->route('admin.categories.index')
                ->with('success', __('Category deleted successfully.'));
        } catch (\Exception $e) {
            throwError(__('Error deleting category'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function trashed(Request $request)
    {
        try {
            isAllowed($request->user());

            // Ajout de la pagination pour optimiser
            $categories = Category::onlyTrashed()->paginate(10);
            return view('admin.categories.trashed', compact('categories'));
        } catch (\Exception $e) {
            throwError(__('Error loading trashed categories'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function restore(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            // Utilisation de withTrashed() pour retrouver les catégories supprimées
            $category = Category::withTrashed()->findOrFail($id);
            $category->restore();

            return redirect()->route('admin.categories.trashed')
                ->with('success', __('Category restored successfully.'));
        } catch (\Exception $e) {
            throwError(__('Error restoring category'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function forceDelete(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            // Utilisation de withTrashed() pour retrouver les catégories supprimées
            $category = Category::withTrashed()->findOrFail($id);
            $category->forceDelete();

            return redirect()->route('admin.categories.trashed')
                ->with('success', __('Category permanently deleted.'));
        } catch (\Exception $e) {
            throwError(__('Error permanently deleting category'), 500, ['details' => $e->getMessage()]);
        }
    }
}
