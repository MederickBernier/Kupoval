<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Log;

class AdminEventsController extends Controller
{
    /**
     * Display a list of events.
     */
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());

            $events = Event::orderBy('start_date', 'asc')->paginate(10);
            return view('admin.events.index', compact('events'));
        } catch (\Exception $e) {
            Log::error('Error loading events list: ' . $e->getMessage());
            return response()->json(['error' => __('Error loading events list')], 500);
        }
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        try {
            isAllowed($request->user());

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'location' => 'required|string|max:255',
            ]);

            $event = Event::create($validated);

            return response()->json(['success' => __('Event created successfully'), 'event' => $event], 201);
        } catch (\Exception $e) {
            Log::error('Error creating event: ' . $e->getMessage());
            return response()->json(['error' => __('Error creating event')], 500);
        }
    }

    /**
     * Update an existing event.
     */
    public function update(Request $request, Event $event)
    {
        try {
            isAllowed($request->user());

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'sometimes|required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'location' => 'sometimes|required|string|max:255',
            ]);

            $event->update($validated);

            return response()->json(['success' => __('Event updated successfully'), 'event' => $event], 200);
        } catch (\Exception $e) {
            Log::error('Error updating event: ' . $e->getMessage());
            return response()->json(['error' => __('Error updating event')], 500);
        }
    }

    /**
     * Soft delete an event.
     */
    public function destroy(Request $request, Event $event)
    {
        try {
            isAllowed($request->user());

            $event->delete();

            return response()->json(['success' => __('Event deleted successfully')], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting event: ' . $e->getMessage());
            return response()->json(['error' => __('Error deleting event')], 500);
        }
    }

    /**
     * Display a list of soft-deleted events.
     */
    public function trashed(Request $request)
    {
        try {
            isAllowed($request->user());

            $events = Event::onlyTrashed()->paginate(10);
            return view('admin.events.trashed', compact('events'));
        } catch (\Exception $e) {
            Log::error('Error loading trashed events: ' . $e->getMessage());
            return response()->json(['error' => __('Error loading trashed events')], 500);
        }
    }

    /**
     * Restore a soft-deleted event.
     */
    public function restore(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $event = Event::onlyTrashed()->find($id);

            if (!$event) {
                return response()->json(['error' => __('Event not found or already restored')], 404);
            }

            $event->restore();

            return response()->json(['success' => __('Event restored successfully')], 200);
        } catch (\Exception $e) {
            Log::error('Error restoring event: ' . $e->getMessage());
            return response()->json(['error' => __('Error restoring event')], 500);
        }
    }

    /**
     * Permanently delete an event.
     */
    public function forceDelete(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $event = Event::onlyTrashed()->find($id);

            if (!$event) {
                return response()->json(['error' => __('Event not found or already deleted')], 404);
            }

            $event->forceDelete();

            return response()->json(['success' => __('Event permanently deleted')], 200);
        } catch (\Exception $e) {
            Log::error('Error permanently deleting event: ' . $e->getMessage());
            return response()->json(['error' => __('Error permanently deleting event')], 500);
        }
    }
}
