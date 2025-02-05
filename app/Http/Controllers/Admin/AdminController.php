<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;

class AdminController extends Controller
{
    public function index(Request $request){
        try{
            isAllowed($request->user());
            return view('admin.dashboard');
        }catch(\Exception $e){
            throwError(__('Unauthorized access'), 403, ['exception' => $e->getMessage()]);
        }
    }

    public function users(Request $request)
    {
        try{
            isAllowed($request->user());

            $users = User::with(['profile', 'orders'])->orderBy('id', 'asc')->paginate(10);

            return view('admin.users.index', [
                'users' => $users
            ]);
        }catch(\Exception $e){
            throwError(__('Unauthorized access'), 403, ['exception' => $e->getMessage()]);
        }
    }

    public function settings(Request $request)
    {
        try{
            isAllowed($request->user());
            $settings = Setting::orderBy('key', 'asc')->paginate(10);

            return view('admin.settings.index', [
                'settings' => $settings,
            ]);
        }catch(\Exception $e){
            throwError(__('Unauthorized access'), 403, ['exception' => $e->getMessage()]);
        }
    }
}
