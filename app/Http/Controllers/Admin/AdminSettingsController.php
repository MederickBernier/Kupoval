<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class AdminSettingsController extends Controller
{
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());

            $settings = Setting::orderBy('key', 'asc')->paginate(10);
            return view('admin.settings.index', [
                'settings' => $settings,
            ]);
        } catch (\Exception $e) {
            throwError(__('Error loading settings list'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        isAllowed($request->user());

        $request->validate([
            'key' => 'required|string|max:255|unique:settings,key',
            'value' => 'required|string',
        ]);

        try {
            Setting::create($request->only(['key', 'value']));

            return redirect()->route('admin.settings.list')->with('success', __('Setting created successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.list')->with('error', __('Failed to create setting.'));
        }
    }

    public function update(Request $request, Setting $setting)
    {
        isAllowed($request->user());

        $request->validate([
            'value' => 'required|string',
        ]);

        try {
            $setting->update($request->only(['value']));

            return redirect()->route('admin.settings.list')->with('success', __('Setting updated successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.list')->with('error', __('Failed to update setting.'));
        }
    }

    public function destroy(Request $request, Setting $setting)
    {
        try {
            isAllowed($request->user());

            $setting->delete();

            return redirect()->route('admin.settings.list')->with('success', __('Setting deleted successfully.'));
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.list')->with('error', __('Failed to delete setting.'));
        }
    }
}
