<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;

class AdminArtistsController extends Controller
{
    /**
     * Display the list of artists.
     */
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());

            $artists = Artist::paginate(10);
            return view('admin.artists.index', compact('artists'));
        } catch (Throwable $e) {
            Log::error("❌ Error loading artists list: " . $e->getMessage());
            return back()->with('error', __('Error loading artists list.'));
        }
    }

    /**
     * Show artist creation form.
     */
    public function create(Request $request)
    {
        try {
            isAllowed($request->user());
            return view('admin.artists.create');
        } catch (Throwable $e) {
            Log::error("❌ Error loading artist creation page: " . $e->getMessage());
            return back()->with('error', __('Error loading artist creation page.'));
        }
    }

    /**
     * Store a new artist.
     */
    public function store(Request $request)
    {
        try {
            isAllowed($request->user());

            $validated = $request->validate($this->validationRules());

            $slug = $this->generateUniqueSlug($validated['name']);
            $validated['slug'] = $slug;

            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('artists', 'public');
            }

            Artist::create($validated);

            return redirect()->route('admin.artists.index')->with('success', __('Artist created successfully.'));
        } catch (Throwable $e) {
            Log::error("❌ Error creating artist: " . $e->getMessage());
            return back()->with('error', __('Error creating artist.'));
        }
    }

    /**
     * Show artist edit page.
     */
    public function edit(Request $request, Artist $artist)
    {
        try {
            isAllowed($request->user());
            return view('admin.artists.edit', compact('artist'));
        } catch (Throwable $e) {
            Log::error("❌ Error loading artist edit page: " . $e->getMessage());
            return back()->with('error', __('Error loading artist edit page.'));
        }
    }

    /**
     * Update an artist.
     */
    public function update(Request $request, Artist $artist)
    {
        try {
            isAllowed($request->user());

            $validated = $request->validate($this->validationRules($artist->id));

            if ($artist->name !== $validated['name']) {
                $validated['slug'] = $this->generateUniqueSlug($validated['name'], $artist->id);
            }

            if ($request->hasFile('photo')) {
                if ($artist->photo) {
                    Storage::disk('public')->delete($artist->photo);
                }
                $validated['photo'] = $request->file('photo')->store('artists', 'public');
            }

            $artist->update($validated);

            return redirect()->route('admin.artists.index')->with('success', __('Artist updated successfully.'));
        } catch (Throwable $e) {
            Log::error("❌ Error updating artist: " . $e->getMessage());
            return back()->with('error', __('Error updating artist.'));
        }
    }

    /**
     * Soft delete an artist.
     */
    public function destroy(Request $request, Artist $artist)
    {
        try {
            isAllowed($request->user());

            if ($artist->artworks()->exists()) {
                return back()->with('error', __('Cannot delete artist with associated artworks.'));
            }

            $artist->delete();

            return redirect()->route('admin.artists.index')->with('success', __('Artist moved to trash successfully.'));
        } catch (Throwable $e) {
            Log::error("❌ Error deleting artist: " . $e->getMessage());
            return back()->with('error', __('Error deleting artist.'));
        }
    }

    /**
     * Display trashed artists.
     */
    public function trashed(Request $request)
    {
        try {
            isAllowed($request->user());

            $artists = Artist::onlyTrashed()->paginate(10);
            return view('admin.artists.trashed', compact('artists'));
        } catch (Throwable $e) {
            Log::error("❌ Error loading trashed artists: " . $e->getMessage());
            return back()->with('error', __('Error loading trashed artists.'));
        }
    }

    /**
     * Restore a soft-deleted artist.
     */
    public function restore(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $artist = Artist::onlyTrashed()->findOrFail($id);

            if (Artist::where('name', $artist->name)->whereNull('deleted_at')->exists()) {
                return back()->with('error', __('An artist with this name already exists.'));
            }

            $artist->restore();

            return redirect()->route('admin.artists.index')->with('success', __('Artist restored successfully.'));
        } catch (Throwable $e) {
            Log::error("❌ Error restoring artist: " . $e->getMessage());
            return back()->with('error', __('Error restoring artist.'));
        }
    }

    /**
     * Permanently delete an artist.
     */
    public function forceDelete(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $artist = Artist::onlyTrashed()->findOrFail($id);

            if ($artist->artworks()->exists()) {
                return back()->with('error', __('Cannot delete artist with associated artworks.'));
            }

            if ($artist->photo) {
                Storage::disk('public')->delete($artist->photo);
            }

            $artist->forceDelete();

            return redirect()->route('admin.artists.trashed')->with('success', __('Artist permanently deleted.'));
        } catch (Throwable $e) {
            Log::error("❌ Error permanently deleting artist: " . $e->getMessage());
            return back()->with('error', __('Error permanently deleting artist.'));
        }
    }

    /**
     * Validation rules.
     */
    private function validationRules($id = null)
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'name' => 'required|string|max:255|unique:artists,name' . ($id ? ",$id" : ''),
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'tiktok' => 'nullable|url|max:255',
        ];
    }

    /**
     * Generate a unique slug.
     */
    private function generateUniqueSlug($name, $id = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (Artist::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
