<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AdminArtistsController extends Controller
{
    public function index(Request $request)
    {
        try {
            isAllowed($request->user());
            $artists = Artist::paginate(10);
            return view('admin.artists.index', compact('artists'));
        } catch (\Exception $e) {
            throwError(__('Error loading artists list'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function create(Request $request)
    {
        try {
            isAllowed($request->user());
            return view('admin.artists.create');
        } catch (\Exception $e) {
            throwError(__('Error loading artist creation page'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            isAllowed($request->user());

            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'name' => 'required|string|max:255|unique:artists,name',
                'bio' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Generate unique slug
            $slug = Str::slug($request->name);
            if (Artist::where('slug', $slug)->exists()) {
                $slug .= '-' . (Artist::where('slug', 'LIKE', "{$slug}%")->count() + 1);
            }

            // Handle photo upload
            $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('artists', 'public') : null;

            // Create artist
            Artist::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name' => $request->name,
                'slug' => $slug,
                'bio' => $request->bio,
                'photo' => $photoPath ? 'storage/' . $photoPath : null,
            ]);

            return redirect()->route('admin.artists.index')->with('success', __('Artist created successfully.'));
        } catch (\Exception $e) {
            throwError(__('Error creating artist'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function edit(Request $request, Artist $artist)
    {
        try {
            isAllowed($request->user());
            return view('admin.artists.edit', compact('artist'));
        } catch (\Exception $e) {
            throwError(__('Error loading artist edit page'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Artist $artist)
    {
        try {
            isAllowed($request->user());

            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'name' => 'required|string|max:255|unique:artists,name,' . $artist->id,
                'bio' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Generate unique slug
            $slug = Str::slug($request->name);
            if (Artist::where('slug', $slug)->where('id', '!=', $artist->id)->exists()) {
                $slug .= '-' . (Artist::where('slug', 'LIKE', "{$slug}%")->count() + 1);
            }

            // Handle photo update
            if ($request->hasFile('photo')) {
                if ($artist->photo) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $artist->photo));
                }
                $photoPath = $request->file('photo')->store('artists', 'public');
                $artist->photo = 'storage/' . $photoPath;
            }

            // Update artist details
            $artist->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name' => $request->name,
                'slug' => $slug,
                'bio' => $request->bio,
            ]);

            return redirect()->route('admin.artists.index')->with('success', __('Artist updated successfully.'));
        } catch (\Exception $e) {
            throwError(__('Error updating artist'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, Artist $artist)
    {
        try {
            isAllowed($request->user());

            // Prevent deletion if artist has artworks
            if ($artist->artworks()->exists()) {
                return back()->with('error', __('Cannot delete artist with associated artworks.'));
            }

            $artist->delete();

            return redirect()->route('admin.artists.index')->with('success', __('Artist moved to trash successfully.'));
        } catch (\Exception $e) {
            throwError(__('Error deleting artist'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function trashed(Request $request)
    {
        try {
            isAllowed($request->user());
            $artists = Artist::onlyTrashed()->paginate(10);
            return view('admin.artists.trashed', compact('artists'));
        } catch (\Exception $e) {
            throwError(__('Error loading trashed artists'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function restore(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $artist = Artist::onlyTrashed()->findOrFail($id);

            // Ensure name doesn't conflict before restoring
            if (Artist::where('name', $artist->name)->whereNull('deleted_at')->exists()) {
                return back()->with('error', __('An artist with this name already exists.'));
            }

            $artist->restore();

            return redirect()->route('admin.artists.index')->with('success', __('Artist restored successfully.'));
        } catch (\Exception $e) {
            throwError(__('Error restoring artist'), 500, ['details' => $e->getMessage()]);
        }
    }

    public function forceDelete(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            $artist = Artist::onlyTrashed()->findOrFail($id);

            // Prevent deletion if artist has artworks
            if ($artist->artworks()->exists()) {
                return back()->with('error', __('Cannot delete artist with associated artworks.'));
            }

            // Delete associated photo
            if ($artist->photo && Storage::disk('public')->exists(str_replace('storage/', '', $artist->photo))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $artist->photo));
            }

            $artist->forceDelete();

            return redirect()->route('admin.artists.trashed')->with('success', __('Artist permanently deleted.'));
        } catch (\Exception $e) {
            throwError(__('Error permanently deleting artist'), 500, ['details' => $e->getMessage()]);
        }
    }
}
