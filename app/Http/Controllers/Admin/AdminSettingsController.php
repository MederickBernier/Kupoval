<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class AdminSettingsController extends Controller
{
    /**
     * Display the list of settings.
     */
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());

            $settings = Setting::orderBy('key', 'asc')->paginate(10);
            return view('admin.settings.index', compact('settings'));
        } catch (\Exception $e) {
            Log::error('Error loading settings list: ' . $e->getMessage());
            return response()->json(['error' => __('Error loading settings list')], 500);
        }
    }

    /**
     * Store a new setting.
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

            return redirect()->route('admin.settings.index')->with('success', __('Setting created successfully'));
        } catch (\Exception $e) {
            Log::error('Error creating setting: ' . $e->getMessage());
            return back()->with('error', __('Failed to create setting'));
        }
    }

    /**
     * Update an existing setting.
     */
    public function update(Request $request, $setting)
    {
        try {
            isAllowed($request->user());

            $validated = $request->validate([
                'value' => 'string|nullable',
            ]);

            // Find setting manually
            $setting = Setting::findOrFail($setting);
            $setting->update($validated);

            return redirect()->route('admin.settings.index')->with('success', __('Setting updated successfully'));
        } catch (\Exception $e) {
            Log::error('Error updating setting: ' . $e->getMessage());
            return back()->with('error', __('Failed to update setting'));
        }
    }

    /**
     * Delete a setting.
     */
    public function destroy(Request $request, Setting $setting)
    {
        try {
            isAllowed($request->user());

            $setting->delete();

            return response()->json(['success' => __('Setting deleted successfully')], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting setting: ' . $e->getMessage());
            return response()->json(['error' => __('Failed to delete setting')], 500);
        }
    }
}
