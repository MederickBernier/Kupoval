<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Throwable;

class AdminSettingsController extends Controller
{
    /**
     * Display a listing of the settings.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());

            $settings = Setting::orderBy('key', 'asc')->paginate(10);
            return view('admin.settings.index', compact('settings'));
        } catch (Throwable $e) {
            Log::error('❌ Error loading settings list: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', __('Error loading settings list.'));
        }
    }

    /**
     * Store a newly created setting in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            isAllowed($request->user());

            $validated = $request->validate([
                'key' => 'required|string|max:255|unique:settings,key',
                'value' => 'string|nullable',
            ]);

            Setting::create($validated);

            return redirect()->route('admin.settings.index')->with('success', __('Setting created successfully.'));
        } catch (Throwable $e) {
            Log::error('❌ Error creating setting: ' . $e->getMessage());
            return back()->with('error', __('Failed to create setting.'));
        }
    }

    /**
     * Update the specified setting in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $validated = $request->validate([
                'value' => 'string|nullable',
            ]);

            $setting = Setting::findOrFail($id);
            $setting->update($validated);

            return redirect()->route('admin.settings.index')->with('success', __('Setting updated successfully.'));
        } catch (Throwable $e) {
            Log::error('❌ Error updating setting: ' . $e->getMessage());
            return back()->with('error', __('Failed to update setting.'));
        }
    }

    /**
     * Remove the specified setting from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Setting $setting)
    {
        try {
            isAllowed($request->user());

            $setting->delete();

            return back()->with('success', __('Setting deleted successfully.'));
        } catch (Throwable $e) {
            Log::error('❌ Error deleting setting: ' . $e->getMessage());
            return back()->with('error', __('Failed to delete setting.'));
        }
    }
}
