<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class AdminEventsController extends Controller
{
    /**
     * Display a listing of the events.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());

            $events = Event::orderBy('start_date', 'asc')->paginate(10);

            return view('admin.events.index', compact('events'));
        } catch (Throwable $e) {
            Log::error("❌ Error loading events list: " . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', __('Error loading events list.'));
        }
    }

    /**
     * Store a newly created event in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
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

            DB::beginTransaction();

            $event = Event::create($validated);

            DB::commit();

            return redirect()->route('admin.events.index')->with('success', __('Event created successfully.'));
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("❌ Error creating event: " . $e->getMessage());
            return back()->with('error', __('Error creating event.'));
        }
    }

    /**
     * Update the specified event in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Event $event
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
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

            DB::beginTransaction();

            $event->update($validated);

            DB::commit();

            return redirect()->route('admin.events.index')->with('success', __('Event updated successfully.'));
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("❌ Error updating event (Event ID: {$event->id}): " . $e->getMessage());
            return back()->with('error', __('Error updating event.'));
        }
    }

    /**
     * Remove the specified event from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Event $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Event $event)
    {
        try {
            isAllowed($request->user());

            $event->delete();

            return redirect()->route('admin.events.index')->with('success', __('Event deleted successfully.'));
        } catch (Throwable $e) {
            Log::error("❌ Error deleting event (Event ID: {$event->id}): " . $e->getMessage());
            return back()->with('error', __('Error deleting event.'));
        }
    }

    /**
     * Display a listing of the trashed events.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @throws \Throwable
     */
    public function trashed(Request $request)
    {
        try {
            isAllowed($request->user());

            $events = Event::onlyTrashed()->paginate(10);

            return view('admin.events.trashed', compact('events'));
        } catch (Throwable $e) {
            Log::error("❌ Error loading trashed events: " . $e->getMessage());
            return redirect()->route('admin.events.index')->with('error', __('Error loading trashed events.'));
        }
    }

    /**
     * Restore a trashed event.
     *
     * @param \Illuminate\Http\Request $request The HTTP request instance.
     * @param int $id The ID of the event to restore.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $event = Event::onlyTrashed()->findOrFail($id);
            $event->restore();

            return redirect()->route('admin.events.trashed')->with('success', __('Event restored successfully.'));
        } catch (Throwable $e) {
            Log::error("❌ Error restoring event (Event ID: {$id}): " . $e->getMessage());
            return back()->with('error', __('Error restoring event.'));
        }
    }

    /**
     * Permanently delete a trashed event.
     *
     * @param \Illuminate\Http\Request $request The current request instance.
     * @param int $id The ID of the event to be permanently deleted.
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the event is not found.
     * @throws \Throwable If any other error occurs during deletion.
     */
    public function forceDelete(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $event = Event::onlyTrashed()->findOrFail($id);
            $event->forceDelete();

            return redirect()->route('admin.events.trashed')->with('success', __('Event permanently deleted.'));
        } catch (Throwable $e) {
            Log::error("❌ Error permanently deleting event (Event ID: {$id}): " . $e->getMessage());
            return back()->with('error', __('Error permanently deleting event.'));
        }
    }
}
