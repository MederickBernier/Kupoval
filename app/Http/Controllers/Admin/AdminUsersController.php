<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class AdminUsersController extends Controller
{
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());

            $users = User::with(['profile', 'orders'])
                ->whereNull('deleted_at')
                ->orderBy('id', 'asc')
                ->paginate(10);

            return view('admin.users.index', [
                'users' => $users,
            ]);
        } catch (\Exception $e) {
            throwError(__('Error loading users list'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, User $user)
    {
        try {
            isAllowed($request->user());

            if ($user->role === 'admin') {
                return response()->json(['error' => __('You cannot delete an admin user.')], 403);
            }

            $user->delete();

            return response()->json(['success' => __('User deleted successfully.')], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => __('Error deleting user'), 'exception' => $e->getMessage()], 500);
        }
    }

    public function trashed(Request $request)
    {
        try {
            isAllowed($request->user());

            $users = User::onlyTrashed()
                ->with(['profile', 'orders'])
                ->orderBy('id', 'asc')
                ->paginate(10);

            return view('admin.users.trashed', [
                'users' => $users,
            ]);
        } catch (\Exception $e) {
            throwError(__('Error loading trashed users list'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function restore(Request $request, User $user)
    {
        try {
            isAllowed($request->user());

            $user->restore();

            return response()->json(['success' => __('User restored successfully')], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => __('Error restoring user'), 'exception' => $e->getMessage()], 500);
        }
    }
}
