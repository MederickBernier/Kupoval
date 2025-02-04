<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;

class AdminController extends Controller
{
    public function index(Request $request){
        isAllowed($request->user());
        return view('admin.dashboard');
    }

    public function users(Request $request)
    {
        isAllowed($request->user());

        $users = User::with(['profile', 'orders'])->orderBy('id', 'asc')->paginate(10);

        return view('admin.users.index', [
            'users' => $users
        ]);
    }

    public function settings(Request $request)
    {
        isAllowed($request->user());
        $settings = Setting::orderBy('key', 'asc')->paginate(10);

        return view('admin.settings.index', [
            'settings' => $settings,
        ]);
    }
}
