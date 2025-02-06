<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Artwork;
use App\Models\Event;

class AdminController extends Controller
{
    public function index(Request $request){
        try{
            isAllowed($request->user());

            $totalUsers = User::count();
            $totalArtworks = Artwork::count();
            $totalEvents = Event::count();

            return view('admin.dashboard',[
                'totalUsers' => $totalUsers,
                'totalArtworks' => $totalArtworks,
                'totalEvents' => $totalEvents
            ]);
        }catch(\Exception $e){
            throwError(__('Unauthorized access'), 403, ['exception' => $e->getMessage()]);
        }
    }
}
