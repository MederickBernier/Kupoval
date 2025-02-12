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
                'name' => ['required', 'string', 'max:255', Rule::unique('categories')->where(
                    fn($query) =>
                    $query->whereRaw('LOWER(name) = ?', [strtolower($request->name)])
                )],
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
                'name' => ['required', 'string', 'max:255', Rule::unique('categories')->where(
                    fn($query) =>
                    $query->whereRaw('LOWER(name) = ?', [strtolower($request->name)])
                )->ignore($category->id)],
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

            // Check if category is linked to any artworks
            if ($category->artworks()->exists()) {
                return back()->with('error', __('This category is assigned to artworks and cannot be deleted.'));
            }

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

            $category = Category::withTrashed()->findOrFail($id);

            // Prevent restoring if a category with the same name already exists
            if (Category::where('name', $category->name)->whereNull('deleted_at')->exists()) {
                return back()->with('error', __('A category with the same name already exists.'));
            }

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

            $category = Category::withTrashed()->findOrFail($id);

            // Prevent force deletion if linked to artworks
            if ($category->artworks()->exists()) {
                return back()->with('error', __('This category is linked to artworks and cannot be permanently deleted.'));
            }

            $category->forceDelete();

            return redirect()->route('admin.categories.trashed')
                ->with('success', __('Category permanently deleted.'));
        } catch (\Exception $e) {
            throwError(__('Error permanently deleting category'), 500, ['details' => $e->getMessage()]);
        }
    }
}
