<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artwork;
use Illuminate\Support\Facades\Storage;
use App\Models\Artist;
use App\Models\Category;
use App\Models\Event;

class AdminArtworksController extends Controller
{
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());

            $artworks = Artwork::latest()->paginate(10);
            return view('admin.artworks.index', compact('artworks'));
        } catch (\Exception $e) {
            throwError(__('Error loading artworks list'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function create(Request $request)
    {
        try {
            isAllowed($request->user());

            $artists = Artist::orderBy('name', 'asc')->get();
            $events = Event::orderBy('start_date', 'asc')->get();
            $categories = Category::all();

            return view('admin.artworks.create', compact('artists', 'events', 'categories'));
        } catch (\Exception $e) {
            throwError(__('Error loading artwork creation page'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            isAllowed($request->user());

            // Convertir les catégories en tableau avant validation
            $request->merge(['categories' => explode(',', $request->categories)]);

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
                'categories'    => 'array',
                'categories.*'  => 'exists:categories,id',
                'image'         => 'nullable|image|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('artworks', 'public');
            }

            $artwork = Artwork::create($validated);
            $artwork->categories()->sync($request->categories ?? []);

            return redirect()->route('admin.artworks.index')->with('success', __('Artwork created successfully'));
        } catch (\Exception $e) {
            throwError(__('Error creating artwork'), 500, ['exception' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Artwork $artwork)
    {
        try {
            isAllowed($request->user());

            // Convertir les catégories en tableau avant validation
            $request->merge(['categories' => explode(',', $request->categories)]);

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
                'categories'    => 'array',
                'categories.*'  => 'exists:categories,id',
                'image'         => 'nullable|image|max:2048',
            ]);

            if ($request->hasFile('image')) {
                if ($artwork->image && Storage::disk('public')->exists($artwork->image)) {
                    Storage::disk('public')->delete($artwork->image);
                }
                $validated['image'] = $request->file('image')->store('artworks', 'public');
            }

            $artwork->update($validated);
            $artwork->categories()->sync($request->categories ?? []);

            return redirect()->route('admin.artworks.index')->with('success', __('Artwork updated successfully'));
        } catch (\Exception $e) {
            throwError(__('Error updating artwork'), 500, ['exception' => $e->getMessage()]);
        }
    }

    public function edit(Request $request, Artwork $artwork)
    {
        try {
            isAllowed($request->user());

            $artists = Artist::orderBy('name', 'asc')->get();
            $events = Event::orderBy('start_date', 'asc')->get();
            $categories = Category::all();

            return view('admin.artworks.edit', compact('artwork', 'artists', 'events', 'categories'));
        } catch (\Exception $e) {
            throwError(__('Error loading artwork edit page'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, Artwork $artwork)
    {
        try {
            isAllowed($request->user());

            $artwork->delete();

            return redirect()->route('admin.artworks.index')->with('success', __('Artwork deleted successfully'));
        } catch (\Exception $e) {
            throwError(__('Error deleting artwork'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function trashed(Request $request)
    {
        try {
            isAllowed($request->user());

            $artworks = Artwork::onlyTrashed()->paginate(10);
            return view('admin.artworks.trashed', compact('artworks'));
        } catch (\Exception $e) {
            throwError(__('Error loading trashed artworks list'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function restore(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $artwork = Artwork::onlyTrashed()->findOrFail($id);
            $artwork->restore();

            return redirect()->route('admin.artworks.index')->with('success', __('Artwork restored successfully'));
        } catch (\Exception $e) {
            throwError(__('Error restoring artwork'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function forceDelete(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $artwork = Artwork::onlyTrashed()->findOrFail($id);

            if ($artwork->image) {
                Storage::disk('public')->delete($artwork->image);
            }

            $artwork->forceDelete();

            return redirect()->route('admin.artworks.trashed')->with('success', __('Artwork permanently removed successfully'));
        } catch (\Exception $e) {
            throwError(__('Error permanently deleting artwork'), 500, ['details' => $e->getMessage()]);
        }
    }
}
