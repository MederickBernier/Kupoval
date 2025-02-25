<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artwork;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArtworkController extends Controller
{
    /**
     * Display the specified artwork.
     */
    public function show(Artwork $artwork)
    {
        try {
            return view('public.artwork.show', compact('artwork'));
        } catch (ModelNotFoundException $e) {
            Log::error("❌ Artwork not found: " . $e->getMessage());

            return redirect()->route('home')
                ->with('error', __('The requested artwork could not be found.'));
        } catch (\Exception $e) {
            Log::error("❌ Failed to load artwork page: " . $e->getMessage());

            return redirect()->route('home')
                ->with('error', __('An error occurred while loading the artwork page.'));
        }
    }
}
