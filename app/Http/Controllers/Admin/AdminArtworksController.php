<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artwork;
use Illuminate\Support\Facades\Storage;
use App\Models\Artist;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class AdminArtworksController extends Controller
{
    /**
     * Display a list of artworks.
     */
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());

            $artworks = Artwork::latest()->paginate(10);
            return view('admin.artworks.index', compact('artworks'));
        } catch (Throwable $e) {
            Log::error("❌ Error loading artworks list: " . $e->getMessage());
            return back()->with('error', __('Error loading artworks list.'));
        }
    }

    /**
     * Show artwork creation form.
     */
    public function create(Request $request)
    {
        try {
            isAllowed($request->user());

            return view('admin.artworks.create', [
                'artists' => Artist::orderBy('name', 'asc')->get(),
                'events' => Event::orderBy('start_date', 'asc')->get(),
                'categories' => Category::all(),
            ]);
        } catch (Throwable $e) {
            Log::error("❌ Error loading artwork creation page: " . $e->getMessage());
            return back()->with('error', __('Error loading artwork creation page.'));
        }
    }

    /**
     * Store new artwork.
     */
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
                'event_id'      => 'nullable|exists:events,id|required_if:is_for_event,true',
                'categories'    => 'sometimes|array',
                'categories.*'  => 'exists:categories,id',
                'image'         => 'nullable|image|max:2048',
            ]);

            DB::beginTransaction();

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('artworks', 'public');
            }

            // Generate a unique slug
            $validated['slug'] = $this->generateUniqueSlug($validated['name']);

            // Create Artwork
            $artwork = Artwork::create($validated);

            // Attach Categories
            $artwork->categories()->sync($request->categories ?? []);

            DB::commit();

            return redirect()->route('admin.artworks.index')->with('success', __('Artwork created successfully.'));
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("❌ Error creating artwork: " . $e->getMessage());
            return back()->with('error', __('Error creating artwork.'));
        }
    }

    /**
     * Show the edit page.
     */
    public function edit(Request $request, Artwork $artwork)
    {
        try {
            isAllowed($request->user());

            return view('admin.artworks.edit', [
                'artwork' => $artwork,
                'artists' => Artist::orderBy('name', 'asc')->get(),
                'events' => Event::orderBy('start_date', 'asc')->get(),
                'categories' => Category::all(),
            ]);
        } catch (Throwable $e) {
            Log::error("❌ Error loading artwork edit page: " . $e->getMessage());
            return back()->with('error', __('Error loading artwork edit page.'));
        }
    }

    /**
     * Update an artwork.
     */
    public function update(Request $request, Artwork $artwork)
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
                'event_id'      => 'nullable|exists:events,id|required_if:is_for_event,true',
                'categories'    => 'sometimes|array',
                'categories.*'  => 'exists:categories,id',
                'image'         => 'nullable|image|max:2048',
            ]);

            DB::beginTransaction();

            if ($request->hasFile('image')) {
                Storage::disk('public')->delete($artwork->image);
                $validated['image'] = $request->file('image')->store('artworks', 'public');
            }

            $artwork->update($validated);
            $artwork->categories()->sync($request->categories ?? []);

            DB::commit();

            return redirect()->route('admin.artworks.index')->with('success', __('Artwork updated successfully.'));
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("❌ Error updating artwork: " . $e->getMessage());
            return back()->with('error', __('Error updating artwork.'));
        }
    }

    /**
     * Soft delete an artwork.
     */
    public function destroy(Request $request, Artwork $artwork)
    {
        try {
            isAllowed($request->user());

            $artwork->delete();

            return redirect()->route('admin.artworks.index')->with('success', __('Artwork deleted successfully.'));
        } catch (Throwable $e) {
            Log::error("❌ Error deleting artwork: " . $e->getMessage());
            return back()->with('error', __('Error deleting artwork.'));
        }
    }

    /**
     * Display trashed artworks.
     */
    public function trashed(Request $request)
    {
        try {
            isAllowed($request->user());

            return view('admin.artworks.trashed', [
                'artworks' => Artwork::onlyTrashed()->paginate(10),
            ]);
        } catch (Throwable $e) {
            Log::error("❌ Error loading trashed artworks: " . $e->getMessage());
            return back()->with('error', __('Error loading trashed artworks.'));
        }
    }

    /**
     * Restore a soft-deleted artwork.
     */
    public function restore(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $artwork = Artwork::onlyTrashed()->findOrFail($id);
            $artwork->restore();

            return redirect()->route('admin.artworks.index')->with('success', __('Artwork restored successfully.'));
        } catch (Throwable $e) {
            Log::error("❌ Error restoring artwork: " . $e->getMessage());
            return back()->with('error', __('Error restoring artwork.'));
        }
    }

    /**
     * Permanently delete an artwork.
     */
    public function forceDelete(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $artwork = Artwork::onlyTrashed()->findOrFail($id);
            Storage::disk('public')->delete($artwork->image);
            $artwork->forceDelete();

            return redirect()->route('admin.artworks.trashed')->with('success', __('Artwork permanently removed.'));
        } catch (Throwable $e) {
            Log::error("❌ Error permanently deleting artwork: " . $e->getMessage());
            return back()->with('error', __('Error permanently deleting artwork.'));
        }
    }

    /**
     * Generate a unique slug for an artwork.
     */
    private function generateUniqueSlug($name)
    {
        $slug = Str::slug($name, '-');
        $originalSlug = $slug;
        $counter = 1;

        while (Artwork::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
