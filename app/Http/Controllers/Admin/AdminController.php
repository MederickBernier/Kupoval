<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class AdminController extends Controller
{
    public function index(Request $request){
        isAllowed($request->user());
        return view('admin.dashboard');
    }

    public function users(Request $request)
    {
        isAllowed($request->user());

        // Charger les profils et les commandes associés aux utilisateurs
        $users = User::with(['profile', 'orders'])->orderBy('id', 'asc')->paginate(10);

        return view('admin.users.index', [
            'users' => $users
        ]);
    }
}
