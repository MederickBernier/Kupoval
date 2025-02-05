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
}
