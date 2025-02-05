<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class AdminSettingsController extends Controller
{
    public function index(Request $request){
        try{
            isAllowed($request->user());
            $settings = Setting::orderBy('key','asc')->paginate(10);

            return view('admin.settings.index', [
                'settings' => $settings,
            ]);
        }catch(\Exception $e){
            throwError(__('Unauthorized access'), 403, ['exception' => $e->getMessage()]);
        }
    }

    public function store(Request $request){
        $request->validate([
            'key' => 'required|string|max:255|unique:settings,key',
            'value' => 'required|string',
        ]);

        try{
            Setting::create([
                'key' => $request->key,
                'value' => $request->value,
            ]);
            return redirect()->route('admin.settings.list')->with('success', __('Setting created successfully'));
        }catch(\Exception $e){
            return redirect()->route('admin.settings.list')->with('errror', __('Faied to create setting'));
        }
    }

    public function update(Request $request, $id){
        $request->validate([
            'value' => 'required|string',
        ]);

        try{
            $setting = Setting::findOrFail($id);
            $setting->update([
                'value' => $request->value,
            ]);

            return redirect()->route('admin.settings.list')->with('success', __('Setting updated successfully'));
        }catch(\Exception $e){
            return redirect()->route('admin.settings.list')->with('errror', __('Faied to update setting'));
        }
    }

    public function destroy($id){
        try{
            $setting = Setting::findOrFail($id);
            $setting->delete();

            return redirect()->route('admin.settings.list')->with('success', __('Setting deleted successfully'));
        }catch(\Exception $e){
            return redirect()->route('admin.settings.list')->with('error', __('Failed to delete setting'));
        }
    }
}
