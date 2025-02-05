<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
class AdminUsersController extends Controller
{
    public function index(){
        try{
            $users = user::with(['profile','orders'])->whereNull('deleted_at')->orderBy('id','asc')->paginate(10);
            return view('admin.users.index',[
                'users' => $users,
            ]);
        }catch(\Exception $e){
            throwError(__('Unauthorized access'), 403, ['exception' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->role === 'admin') {
                return response()->json(['error' => __('You cannot delete an admin user.')], 403);
            }

            $user->delete();

            return response()->json(['success' => __('User deleted successfully.')], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => __('Error deleting user'), 'exception' => $e->getMessage()], 500);
        }
    }

    public function trashed(){
        try{
            $users = User::onlyTrashed()->with(['profile','orders'])->orderBy('id','asc')->paginate(10);

            return view('admin.users.trashed',[
                'users' => $users,
            ]);
        }catch(\Exception $e){
            throwError(__('Unauthorized access'), 403, ['exception' => $e->getMessage()]);
        }
    }

    public function restore($id){
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();

            return response()->json(['success' => __('User restored successfully')], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => __('Error restoring user'), 'exception' => $e->getMessage()], 500);
        }
    }
}
