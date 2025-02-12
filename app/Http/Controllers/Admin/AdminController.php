<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Artwork;
use App\Models\Event;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Ensure user has proper authorization
            isAllowed($request->user());

            try {
                // Fetch dashboard statistics
                $totalUsers = User::count();
                $totalArtworks = Artwork::count();
                $totalEvents = Event::count();

                return view('admin.dashboard', compact('totalUsers', 'totalArtworks', 'totalEvents'));
            } catch (\Exception $dbException) {
                // Log database errors separately
                Log::error('Error fetching admin dashboard stats: ' . $dbException->getMessage());

                return response()->json([
                    'error' => __('Error loading dashboard data. Please try again.'),
                    'details' => $dbException->getMessage()
                ], 500);
            }
        } catch (\Exception $authException) {
            // Log unauthorized attempts
            Log::warning('Unauthorized admin dashboard access attempt by user ID: ' . $request->user()->id);

            return response()->json([
                'error' => __('Unauthorized access'),
                'details' => $authException->getMessage()
            ], 403);
        }
    }
}
