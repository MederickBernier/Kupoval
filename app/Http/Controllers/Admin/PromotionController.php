<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());
            $promotions = Promotion::latest()->paginate(10);
            return view('admin.promotions.index', [
                'promotions' => $promotions
            ]);
        } catch (\Exception $e) {
            throwError(__('Error loading promotions list'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function create(Request $request)
    {
        try {
            isAllowed($request->user());
            return view('admin.promotions.create');
        } catch (\Exception $e) {
            throwError(__('Error loading promotion creation form'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function edit(Request $request, Promotion $promotion)
    {
        try {
            isAllowed($request->user());
            return view('admin.promotions.edit', [
                'promotion' => $promotion
            ]);
        } catch (\Exception $e) {
            throwError(__('Error loading promotion edit form'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function show(Request $request, Promotion $promotion)
    {
        try {
            isAllowed($request->user());

            return view('admin.promotions.show', [
                'promotion' => $promotion
            ]);
        } catch (\Exception $e) {
            throwError(__('Error loading promotion details'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            isAllowed($request->user());

            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'discount_percentage' => 'required|numeric|min:0|max:100',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'code' => 'nullable|string|max:20|unique:promotions,code',
            ]);

            Promotion::create([
                'code' => $request->code ?? strtoupper(uniqid('PROMO_')),
                'name' => $request->name,
                'description' => $request->description,
                'discount_percentage' => $request->discount_percentage,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'created_by' => $request->user()->id,
            ]);

            return redirect()->route('admin.promotions.index')->with('success', 'Promotion created successfully.');
        } catch (\Exception $e) {
            throwError(__('Error creating promotion'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Promotion $promotion)
    {
        try {
            isAllowed($request->user());

            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'discount_percentage' => 'required|numeric|min:0|max:100',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);

            $promotion->update([
                'name' => $request->name,
                'description' => $request->description,
                'discount_percentage' => $request->discount_percentage,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            return redirect()->route('admin.promotions.index')->with('success', __('Promotion updated successfully'));
        } catch (\Exception $e) {
            throwError(__('Error updating promotion'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, Promotion $promotion)
    {
        try {
            isAllowed($request->user());

            $promotion->delete();
            return redirect()->route('admin.promotions.index')->with('success', __('Promotion deleted successfully'));
        } catch (\Exception $e) {
            throwError(__('Error deleting promotion'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function trashed(Request $request)
    {
        try {
            isAllowed($request->user());

            $promotions = Promotion::onlyTrashed()->paginate(10);

            return view('admin.promotions.trashed', [
                'promotions' => $promotions
            ]);
        } catch (\Exception $e) {
            throwError(__('Error loading trashed promotions list'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function restore(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $promotion = Promotion::onlyTrashed()->findOrFail($id);
            $promotion->restore();

            return redirect()->route('admin.promotions.trashed')->with('success', __('Promotion restored successfully'));
        } catch (\Exception $e) {
            throwError(__('Error restoring promotion'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function forceDelete(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $promotion = Promotion::onlyTrashed()->findOrFail($id);
            $promotion->forceDelete();

            return redirect()->route('admin.promotions.trashed')->with('success', __('Promotion deleted permanently'));
        } catch (\Exception $e) {
            throwError(__('Error deleting promotion permanently'), 500, ['details' => $e->getMessage()]);
        }
    }
}
