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
     * Display a listing of the artists.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
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
     * Display the artist creation page.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
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
     * Store a newly created artist in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
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

            // Handle array fields - convert comma-separated strings to arrays if needed
            if (isset($validated['specialties']) && is_string($validated['specialties'])) {
                $validated['specialties'] = array_filter(array_map('trim', explode(',', $validated['specialties'])));
            }
            if (isset($validated['techniques']) && is_string($validated['techniques'])) {
                $validated['techniques'] = array_filter(array_map('trim', explode(',', $validated['techniques'])));
            }

            Artist::create($validated);

            return redirect()->route('admin.artists.index')->with('success', __('Artist created successfully.'));
        } catch (Throwable $e) {
            Log::error("❌ Error creating artist: " . $e->getMessage());
            return back()->with('error', __('Error creating artist.'));
        }
    }

    /**
     * Display the form for editing the specified artist.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Artist $artist
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
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
     * Update the specified artist in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Artist  $artist
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
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

            // Handle array fields - convert comma-separated strings to arrays if needed
            if (isset($validated['specialties']) && is_string($validated['specialties'])) {
                $validated['specialties'] = array_filter(array_map('trim', explode(',', $validated['specialties'])));
            }
            if (isset($validated['techniques']) && is_string($validated['techniques'])) {
                $validated['techniques'] = array_filter(array_map('trim', explode(',', $validated['techniques'])));
            }

            $artist->update($validated);

            return redirect()->route('admin.artists.index')->with('success', __('Artist updated successfully.'));
        } catch (Throwable $e) {
            Log::error("❌ Error updating artist: " . $e->getMessage());
            return back()->with('error', __('Error updating artist.'));
        }
    }

    /**
     * Remove the specified artist from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Artist $artist
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
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
     * Display a listing of the trashed artists.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
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
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
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
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
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
     * Get the validation rules for the artist form.
     *
     * @param int|null $id The ID of the artist being validated (optional).
     * @return array The validation rules.
     */
    private function validationRules($id = null)
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'name' => 'required|string|max:255|unique:artists,name' . ($id ? ",$id" : ''),
            'bio' => 'nullable|string',
            'artist_statement' => 'nullable|string|max:5000',
            'exhibition_history' => 'nullable|string|max:10000',
            'awards' => 'nullable|string|max:5000',
            'studio_location' => 'nullable|string|max:255',
            'profile_video_url' => 'nullable|url|max:500',
            'specialties' => 'nullable|array',
            'specialties.*' => 'string|max:100',
            'techniques' => 'nullable|array',
            'techniques.*' => 'string|max:100',
            'experience_years' => 'nullable|integer|min:0|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:500',
            'facebook' => 'nullable|url|max:500',
            'twitter' => 'nullable|url|max:500',
            'instagram' => 'nullable|url|max:500',
            'tiktok' => 'nullable|url|max:500',
            'youtube' => 'nullable|url|max:500',
        ];
    }

    /**
     * Generate a unique slug for an artist based on their name.
     *
     * This method creates a URL-friendly slug from the given name and ensures
     * its uniqueness by appending a counter if necessary. If an ID is provided,
     * it will exclude that ID from the uniqueness check, which is useful for
     * updating existing records.
     *
     * @param string $name The name to generate the slug from.
     * @param int|null $id The ID to exclude from the uniqueness check (optional).
     * @return string The unique slug.
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
