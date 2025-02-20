<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AdminUsersController extends Controller
{
    /**
     * Display a list of users.
     */
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());

            $users = User::with([
                'profile',
                'profile.billingAddress',  // ✅ Ensure billingAddress is included
                'profile.shippingAddresses', // ✅ Ensure shippingAddresses are included
                'orders'
            ])->orderBy('id', 'asc')->paginate(10);

            return view('admin.users.index', compact('users'));
        } catch (\Exception $e) {
            Log::error('Error loading users list: ' . $e->getMessage());
            return response()->json(['error' => __('Error loading users list')], 500);
        }
    }

    /**
     * Soft delete a user.
     */
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
            Log::error('Error deleting user: ' . $e->getMessage());
            return response()->json(['error' => __('Error deleting user')], 500);
        }
    }

    /**
     * Display a list of trashed (soft-deleted) users.
     */
    public function trashed(Request $request)
    {
        try {
            isAllowed($request->user());

            $users = User::onlyTrashed()
                ->with(['profile', 'orders'])
                ->orderBy('id', 'asc')
                ->paginate(10);

            return view('admin.users.trashed', compact('users'));
        } catch (\Exception $e) {
            Log::error('Error loading trashed users: ' . $e->getMessage());
            return response()->json(['error' => __('Error loading trashed users list')], 500);
        }
    }

    /**
     * Restore a soft-deleted user.
     */
    public function restore(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();

            return response()->json(['success' => __('User restored successfully')], 200);
        } catch (\Exception $e) {
            Log::error('Error restoring user: ' . $e->getMessage());
            return response()->json(['error' => __('Error restoring user')], 500);
        }
    }

    /**
     * Permanently delete a user.
     */
    public function forceDelete(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $user = User::onlyTrashed()->findOrFail($id);

            if ($user->role === 'admin') {
                return response()->json(['error' => __('You cannot permanently delete an admin.')], 403);
            }

            $user->forceDelete();

            return response()->json(['success' => __('User permanently deleted.')], 200);
        } catch (\Exception $e) {
            Log::error('Error permanently deleting user: ' . $e->getMessage());
            return response()->json(['error' => __('Error permanently deleting user')], 500);
        }
    }
}
