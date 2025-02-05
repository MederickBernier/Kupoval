<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class AdminEventsController extends Controller
{
    /**
     * Affiche la liste des événements.
     */
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());
            $events = Event::orderBy('start_date', 'asc')->paginate(10);
            return view('admin.events.index', compact('events'));
        } catch (\Exception $e) {
            throwError(__("Unauthorized access"), 403, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * Stocke un nouvel événement.
     */
    public function store(Request $request)
    {
        try {
            isAllowed($request->user());

            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'location' => 'required|string|max:255',
            ]);

            Event::create($request->all());

            return response()->json(['success' => __('Event created successfully')], 200);
        } catch (\Exception $e) {
            throwError(__("Error creating event"), 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * Met à jour un événement existant.
     */
    public function update(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'location' => 'required|string|max:255',
            ]);

            $event = Event::findOrFail($id);
            $event->update($request->all());

            return redirect()->route('admin.events.index')->with('success', __('Event updated successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Error updating event: ') . $e->getMessage());
        }
    }

    /**
     * Supprime un événement (soft delete).
     */
    public function destroy($id)
    {
        try {
            isAllowed(auth()->user());

            $event = Event::findOrFail($id);
            $event->delete();

            return response()->json(['success' => __('Event deleted successfully')], 200);
        } catch (\Exception $e) {
            throwError(__("Error deleting event"), 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * Affiche les événements supprimés.
     */
    public function trashed(Request $request)
    {
        try {
            isAllowed($request->user());

            $events = Event::onlyTrashed()->paginate(10);
            return view('admin.events.trashed', compact('events'));
        } catch (\Exception $e) {
            throwError(__("Unauthorized access"), 403, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * Restaure un événement supprimé.
     */
    public function restore($id)
    {
        try {
            isAllowed(auth()->user());

            $event = Event::onlyTrashed()->findOrFail($id);
            $event->restore();

            return response()->json(['success' => __('Event restored successfully')], 200);
        } catch (\Exception $e) {
            throwError(__("Error restoring event"), 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * Supprime définitivement un événement.
     */
    public function forceDelete($id)
    {
        try {
            isAllowed(auth()->user());

            $event = Event::onlyTrashed()->findOrFail($id);
            $event->forceDelete();

            return response()->json(['success' => __('Event permanently deleted')], 200);
        } catch (\Exception $e) {
            throwError(__("Error permanently deleting event"), 500, ['exception' => $e->getMessage()]);
        }
    }
}
