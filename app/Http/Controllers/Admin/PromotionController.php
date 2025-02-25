<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());

            $promotions = Promotion::latest()->paginate(10);

            return view('admin.promotions.index', compact('promotions'));
        } catch (Throwable $e) {
            Log::error('❌ Error loading promotions list: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', __('Error loading promotions list.'));
        }
    }

    public function create(Request $request)
    {
        try {
            isAllowed($request->user());
            return view('admin.promotions.create');
        } catch (Throwable $e) {
            Log::error('❌ Error loading promotion creation form: ' . $e->getMessage());
            return back()->with('error', __('Error loading promotion creation form.'));
        }
    }

    public function edit(Request $request, Promotion $promotion)
    {
        try {
            isAllowed($request->user());
            return view('admin.promotions.edit', compact('promotion'));
        } catch (Throwable $e) {
            Log::error('❌ Error loading promotion edit form: ' . $e->getMessage());
            return back()->with('error', __('Error loading promotion edit form.'));
        }
    }

    public function show(Request $request, Promotion $promotion)
    {
        try {
            isAllowed($request->user());

            return view('admin.promotions.show', compact('promotion'));
        } catch (Throwable $e) {
            Log::error('❌ Error loading promotion details: ' . $e->getMessage());
            return back()->with('error', __('Error loading promotion details.'));
        }
    }

    public function store(Request $request)
    {
        try {
            isAllowed($request->user());

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'discount_percentage' => 'required|numeric|min:0|max:100',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'code' => 'nullable|string|max:20|unique:promotions,code',
            ]);

            Promotion::create(array_merge($validated, [
                'code' => $request->code ?? strtoupper(uniqid('PROMO_')),
                'created_by' => $request->user()->id,
            ]));

            return redirect()->route('admin.promotions.index')->with('success', __('Promotion created successfully.'));
        } catch (Throwable $e) {
            Log::error('❌ Error creating promotion: ' . $e->getMessage());
            return back()->with('error', __('Error creating promotion.'));
        }
    }

    public function update(Request $request, Promotion $promotion)
    {
        try {
            isAllowed($request->user());

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'discount_percentage' => 'required|numeric|min:0|max:100',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);

            $promotion->update($validated);

            return redirect()->route('admin.promotions.index')->with('success', __('Promotion updated successfully.'));
        } catch (Throwable $e) {
            Log::error('❌ Error updating promotion: ' . $e->getMessage());
            return back()->with('error', __('Error updating promotion.'));
        }
    }

    public function destroy(Request $request, Promotion $promotion)
    {
        try {
            isAllowed($request->user());

            $promotion->delete();

            return redirect()->route('admin.promotions.index')->with('success', __('Promotion deleted successfully.'));
        } catch (Throwable $e) {
            Log::error('❌ Error deleting promotion: ' . $e->getMessage());
            return back()->with('error', __('Error deleting promotion.'));
        }
    }

    public function trashed(Request $request)
    {
        try {
            isAllowed($request->user());

            $promotions = Promotion::onlyTrashed()->paginate(10);

            return view('admin.promotions.trashed', compact('promotions'));
        } catch (Throwable $e) {
            Log::error('❌ Error loading trashed promotions list: ' . $e->getMessage());
            return back()->with('error', __('Error loading trashed promotions list.'));
        }
    }

    public function restore(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $promotion = Promotion::onlyTrashed()->findOrFail($id);
            $promotion->restore();

            return redirect()->route('admin.promotions.trashed')->with('success', __('Promotion restored successfully.'));
        } catch (Throwable $e) {
            Log::error('❌ Error restoring promotion: ' . $e->getMessage());
            return back()->with('error', __('Error restoring promotion.'));
        }
    }

    public function forceDelete(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $promotion = Promotion::onlyTrashed()->findOrFail($id);
            $promotion->forceDelete();

            return redirect()->route('admin.promotions.trashed')->with('success', __('Promotion deleted permanently.'));
        } catch (Throwable $e) {
            Log::error('❌ Error deleting promotion permanently: ' . $e->getMessage());
            return back()->with('error', __('Error deleting promotion permanently.'));
        }
    }
}
