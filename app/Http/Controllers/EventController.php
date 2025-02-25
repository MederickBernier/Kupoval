<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Exception;

class EventController extends Controller
{
    /**
     * Display event details.
     */
    public function show($id)
    {
        try {
            Log::info("🔹 Fetching event details for ID: {$id}");

            // ✅ Fetch the event, ensuring it exists
            $event = Event::findOrFail($id);

            Log::info("✅ Event found: {$event->title} (ID: {$event->id})");

            return view('public.event.show', compact('event'));
        } catch (ModelNotFoundException $e) {
            Log::warning("⚠️ Event not found (ID: {$id})");
            return redirect()->route('home')->with('error', __('Event not found.'));
        } catch (QueryException $e) {
            Log::error("❌ Database error while fetching event (ID: {$id}): " . $e->getMessage());
            return redirect()->route('home')->with('error', __('Database error while loading the event.'));
        } catch (Exception $e) {
            Log::error("❌ Unexpected error loading event (ID: {$id}): " . $e->getMessage());
            return redirect()->route('home')->with('error', __('An unexpected error occurred while loading the event.'));
        } finally {
            Log::info("🔍 Event lookup completed for ID: {$id}");
        }
    }
}
