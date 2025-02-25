<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class AdminCategoriesController extends Controller
{
    /**
     * Display a list of categories.
     */
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());

            $categories = Category::paginate(10);
            return view('admin.categories.index', compact('categories'));
        } catch (Throwable $e) {
            Log::error("❌ Error loading categories list: " . $e->getMessage());
            return back()->with('error', __('Error loading categories list.'));
        }
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        try {
            isAllowed($request->user());

            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('categories')->where(
                        fn($query) =>
                        $query->whereRaw('LOWER(name) = ?', [strtolower($request->name)])
                    )
                ],
                'description' => 'nullable|string',
            ]);

            DB::beginTransaction();

            Category::create($validated);

            DB::commit();
            return redirect()->route('admin.categories.index')
                ->with('success', __('Category created successfully.'));
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("❌ Error creating category: " . $e->getMessage());
            return back()->with('error', __('Error creating category.'));
        }
    }

    /**
     * Show the category edit page.
     */
    public function edit(Request $request, Category $category)
    {
        try {
            isAllowed($request->user());

            return view('admin.categories.edit', compact('category'));
        } catch (Throwable $e) {
            Log::error("❌ Error loading category edit page: " . $e->getMessage());
            return back()->with('error', __('Error loading category edit page.'));
        }
    }

    /**
     * Update a category.
     */
    public function update(Request $request, Category $category)
    {
        try {
            isAllowed($request->user());

            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('categories')->where(
                        fn($query) =>
                        $query->whereRaw('LOWER(name) = ?', [strtolower($request->name)])
                    )->ignore($category->id)
                ],
                'description' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $category->update($validated);

            DB::commit();
            return redirect()->route('admin.categories.index')
                ->with('success', __('Category updated successfully.'));
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("❌ Error updating category: " . $e->getMessage());
            return back()->with('error', __('Error updating category.'));
        }
    }

    /**
     * Soft delete a category.
     */
    public function destroy(Request $request, Category $category)
    {
        try {
            isAllowed($request->user());

            if ($category->artworks()->exists()) {
                return back()->with('error', __('This category is assigned to artworks and cannot be deleted.'));
            }

            $category->delete();

            return redirect()->route('admin.categories.index')
                ->with('success', __('Category deleted successfully.'));
        } catch (Throwable $e) {
            Log::error("❌ Error deleting category: " . $e->getMessage());
            return back()->with('error', __('Error deleting category.'));
        }
    }

    /**
     * Display a list of trashed (soft deleted) categories.
     */
    public function trashed(Request $request)
    {
        try {
            isAllowed($request->user());

            $categories = Category::onlyTrashed()->paginate(10);
            return view('admin.categories.trashed', compact('categories'));
        } catch (Throwable $e) {
            Log::error("❌ Error loading trashed categories: " . $e->getMessage());
            return back()->with('error', __('Error loading trashed categories.'));
        }
    }

    /**
     * Restore a soft-deleted category.
     */
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
        } catch (Throwable $e) {
            Log::error("❌ Error restoring category: " . $e->getMessage());
            return back()->with('error', __('Error restoring category.'));
        }
    }

    /**
     * Permanently delete a category.
     */
    public function forceDelete(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $category = Category::withTrashed()->findOrFail($id);

            if ($category->artworks()->exists()) {
                return back()->with('error', __('This category is linked to artworks and cannot be permanently deleted.'));
            }

            $category->forceDelete();

            return redirect()->route('admin.categories.trashed')
                ->with('success', __('Category permanently deleted.'));
        } catch (Throwable $e) {
            Log::error("❌ Error permanently deleting category: " . $e->getMessage());
            return back()->with('error', __('Error permanently deleting category.'));
        }
    }
}
