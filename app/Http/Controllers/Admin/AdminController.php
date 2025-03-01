<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Artwork;
use App\Models\Event;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Throwable;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with various statistics.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());

            DB::beginTransaction();

            $stats = DB::table('users')->selectRaw("
                (SELECT COUNT(*) FROM users) as total_users,
                (SELECT COUNT(*) FROM artworks) as total_artworks,
                (SELECT COUNT(*) FROM events) as total_events,
                (SELECT COUNT(*) FROM orders) as total_orders
            ")->first();

            $orderStatusCounts = Order::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');

            DB::commit();

            return view('admin.dashboard', [
                'totalUsers' => $stats->total_users,
                'totalArtworks' => $stats->total_artworks,
                'totalEvents' => $stats->total_events,
                'totalOrders' => $stats->total_orders,
                'orderStatusCounts' => $orderStatusCounts,
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error("❌ Error loading admin dashboard: " . $e->getMessage(), [
                'user_id' => $request->user()->id ?? 'guest',
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('admin.dashboard')->with('error', __('Error loading dashboard data.'));
        }
    }
}
