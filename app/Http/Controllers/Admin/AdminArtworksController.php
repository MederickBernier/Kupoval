<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artwork;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Artist;
use App\Models\Event;

class AdminArtworksController extends Controller
{
    public function index(Request $request){
        try{
            isAllowed($request->user());
            $artworks = Artwork::latest()->paginate(10);

            return view('admin.artworks.index',[
                'artworks' => $artworks,
            ]);
        }catch(\Exception $e){
            throwError(__('Unauthorized access'), 403, ['exception' => $e->getMessage()]);
        }
    }

    public function create(Request $request)
    {
        try {
            isAllowed($request->user());

            $artists = Artist::orderBy('name', 'asc')->get();
            $events = Event::orderBy('start_date', 'asc')->get();

            return view('admin.artworks.create', compact('artists', 'events'));
        } catch (\Exception $e) {
            throwError(__('Error loading artwork creation page'), 500, ['exception' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            isAllowed($request->user());

            $validated = $request->validate([
                'artist_id'     => 'required|exists:artists,id',
                'name'          => 'required|string|max:255',
                'description'   => 'nullable|string',
                'height'        => 'nullable|numeric|min:0',
                'width'         => 'nullable|numeric|min:0',
                'initial_price' => 'nullable|numeric|min:0',
                'is_on_sale'    => 'boolean',
                'is_featured'   => 'boolean',
                'is_for_event'  => 'boolean',
                'event_id'      => 'nullable|exists:events,id',
                'image'         => 'required|image|max:2048',
            ]);

            $validated['height'] = $validated['height'] ?? 0;
            $validated['width'] = $validated['width'] ?? 0;
            $validated['is_on_sale'] = $validated['is_on_sale'] ?? 0;
            $validated['is_featured'] = $validated['is_featured'] ?? 0;
            $validated['is_for_event'] = $validated['is_for_event'] ?? 0;

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('artworks', 'public');
            }

            Artwork::create($validated);

            return redirect()->route('admin.artworks.index')->with('success', __('Artwork created successfully'));
        } catch (\Exception $e) {
            return back()->withErrors(__('Error creating artwork: ') . $e->getMessage());
        }
    }

    public function edit(Request $request, $id){
        try{
            isAllowed($request->user());

            $artwork = Artwork::findOrFail($id);
            $artists = Artist::orderBy('name', 'asc')->get();
            $events = Event::orderBy('start_date', 'asc')->get();

            return view('admin.artworks.edit',[
                'artwork' => $artwork,
                'artists' => $artists,
                'events' => $events,
            ]);
        }catch(\Exception $e){
            throwError(__('Unauthorized access'), 403, ['exception' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $artwork = Artwork::findOrFail($id);

            $validated = $request->validate([
                'artist_id'     => 'required|exists:artists,id',
                'name'          => 'required|string|max:255',
                'description'   => 'nullable|string',
                'height'        => 'nullable|numeric|min:0',
                'width'         => 'nullable|numeric|min:0',
                'initial_price' => 'nullable|numeric|min:0',
                'is_on_sale'    => 'boolean',
                'is_featured'   => 'boolean',
                'is_for_event'  => 'boolean',
                'event_id'      => 'nullable|exists:events,id',
                'image'         => 'nullable|image|max:2048',
            ]);

            if ($request->hasFile('image')) {
                if ($artwork->image && Storage::disk('public')->exists($artwork->image)) {
                    Storage::disk('public')->delete($artwork->image);
                }
                $validated['image'] = $request->file('image')->store('artworks', 'public');
            }

            $artwork->update($validated);

            return redirect()->route('admin.artworks.index')->with('success', __('Artwork updated successfully'));
        } catch (\Exception $e) {
            throwError(__('Error updating artwork'), 500, ['exception' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, $id){
        try{
            $artwork = Artwork::findOrFail($id);
            $artwork->delete();
            return redirect()->route('admin.artworks.index')->with('success', __('Artwork deleted successfully'));
        }catch(\Exception $e){
            throwError(__('Error deleting artwork'), 500, ['exception' => $e->getMessage()]);
        }
    }

    public function trashed(Request $request){
        try{
            isAllowed($request->user());
            $artworks = Artwork::onlyTrashed()->paginate(10);
            return view('admin.artworks.trashed',[
                'artworks' => $artworks,
            ]);
        }catch(\Exception $e){
            throwError(__('Unauthorized access'), 403, ['exception' => $e->getMessage()]);
        }
    }

    public function restore(Request $request, $id){
        try{
            isAllowed($request->user());

            $artwork = Artwork::onlyTrashed()->findOrFail($id);
            $artwork->restore();

            return redirect()->route('admin.artworks.index')->with('success', __('Artwork restored successfully'));
        }catch(\Exception $e){
            throwError(__('Unauthorized access'), 403, ['exception' => $e->getMessage()]);
        }
    }

    public function forceDelete(Request $request, $id){
        try{
            $artwork = Artwork::onlyTrashed()->findOrFail($id);

            if($artwork->image){
                Storage::disk('public','artworks')->delete($artwork->image);
            }

            $artwork->forceDelete();

            return redirect()->route('admin.artworks.trashed')->with('success', 'Artwork permanently removed successfully');
        }catch(\Exception $e){
            throwError(__('Unauthorized access'), 403, ['exception' => $e->getMessage()]);
        }
    }
}
