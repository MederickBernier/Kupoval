<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class PromotionController extends Controller
{
    /**
     * Display a listing of the promotions.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
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

    /**
     * Display the form for creating a new promotion.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
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

    /**
     * Display the form for editing the specified promotion.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Promotion $promotion
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
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

    /**
     * Display the specified promotion.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Promotion $promotion
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Store a newly created promotion in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
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

    /**
     * Update the specified promotion in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Promotion $promotion
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
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

    /**
     * Remove the specified promotion from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Promotion  $promotion
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
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

    /**
     * Display a listing of the trashed promotions.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Restore a trashed promotion.
     *
     * @param \Illuminate\Http\Request $request The current request instance.
     * @param int $id The ID of the promotion to restore.
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the promotion is not found.
     * @throws \Throwable If any other error occurs during the restoration process.
     */
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

    /**
     * Permanently delete a promotion.
     *
     * @param \Illuminate\Http\Request $request The current request instance.
     * @param int $id The ID of the promotion to be deleted.
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the promotion is not found.
     * @throws \Throwable If any other error occurs during deletion.
     */
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
