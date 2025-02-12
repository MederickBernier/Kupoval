<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    /**
     * Display event details.
     */
    public function show($id)
    {
        try {
            // Fetch the event, ensuring it exists
            $event = Event::findOrFail($id);

            return view('public.event.show', compact('event'));
        } catch (\Exception $e) {
            Log::error('Failed to load event details: ' . $e->getMessage());
            return redirect()->route('home')->with('error', __('Event not found or an error occurred.'));
        }
    }
}
