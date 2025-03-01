<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Throwable;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());

            $users = User::with([
                'profile',
                'profile.billingAddress',
                'profile.shippingAddresses',
                'orders'
            ])->orderBy('id', 'asc')->paginate(10);

            return view('admin.users.index', compact('users'));
        } catch (Throwable $e) {
            Log::error('❌ Error loading users list: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', __('Error loading users list.'));
        }
    }

    /**
     * Delete the specified user.
     *
     * @param \Illuminate\Http\Request $request The current request instance.
     * @param \App\Models\User $user The user instance to be deleted.
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable If an error occurs during deletion.
     */
    public function destroy(Request $request, User $user)
    {
        try {
            isAllowed($request->user());

            if ($user->role === 'admin') {
                return back()->with('error', __('You cannot delete an admin user.'));
            }

            $user->delete();

            return back()->with('success', __('User deleted successfully.'));
        } catch (Throwable $e) {
            Log::error('❌ Error deleting user: ' . $e->getMessage());
            return back()->with('error', __('Error deleting user.'));
        }
    }

    /**
     * Display a listing of trashed users.
     *
     * This method retrieves users that have been soft deleted (trashed) and displays them
     * in a paginated format. It also handles any exceptions that may occur during the process.
     *
     * @param \Illuminate\Http\Request $request The incoming request instance.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse The view displaying the trashed users or a redirect response with an error message.
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
        } catch (Throwable $e) {
            Log::error('❌ Error loading trashed users: ' . $e->getMessage());
            return back()->with('error', __('Error loading trashed users list.'));
        }
    }

    /**
     * Restore a soft-deleted user.
     *
     * @param \Illuminate\Http\Request $request The current request instance.
     * @param int $id The ID of the user to restore.
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the user is not found.
     * @throws \Throwable If any other error occurs during the restore process.
     */
    public function restore(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();

            return back()->with('success', __('User restored successfully.'));
        } catch (Throwable $e) {
            Log::error('❌ Error restoring user: ' . $e->getMessage());
            return back()->with('error', __('Error restoring user.'));
        }
    }

    /**
     * Permanently delete a user.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $user = User::onlyTrashed()->findOrFail($id);

            if ($user->role === 'admin') {
                return back()->with('error', __('You cannot permanently delete an admin.'));
            }

            $user->forceDelete();

            return back()->with('success', __('User permanently deleted.'));
        } catch (Throwable $e) {
            Log::error('❌ Error permanently deleting user: ' . $e->getMessage());
            return back()->with('error', __('Error permanently deleting user.'));
        }
    }
}
