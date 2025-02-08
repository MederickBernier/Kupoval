<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artist;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

            $slug = Str::slug($request->name);
            if (Artist::where('slug', $slug)->exists()) {
                $slug .= '-' . (Artist::where('slug', 'LIKE', "{$slug}%")->count() + 1);
            }

            $photoPath = $request->file('photo') ? $request->file('photo')->store('artists', 'public') : null;

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

            $slug = Str::slug($request->name);
            if (Artist::where('slug', $slug)->where('id', '!=', $artist->id)->exists()) {
                $slug .= '-' . (Artist::where('slug', 'LIKE', "{$slug}%")->count() + 1);
            }

            if ($request->hasFile('photo')) {
                if ($artist->photo) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $artist->photo));
                }
                $photoPath = $request->file('photo')->store('artists', 'public');
                $artist->photo = 'storage/' . $photoPath;
            }

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

            // Find soft-deleted artist
            $artist = Artist::onlyTrashed()->where('id', $id)->first();

            if (!$artist) {
                return response()->json(['error' => __('Artist not found or not in trash.')], 404);
            }

            // Restore the artist
            $artist->restore();

            return response()->json(['success' => __('Artist restored successfully.')], 200);

        } catch (\Exception $e) {
            \Log::error('Error in restore: ' . $e->getMessage());
            return response()->json(['error' => __('Error restoring artist.')], 500);
        }
    }

    public function forceDelete(Request $request, $id)
    {
        try {
            isAllowed($request->user());

            // Attempt to find the soft-deleted artist
            $artist = Artist::onlyTrashed()->where('id', $id)->first();

            if (!$artist) {
                return response()->json(['error' => __('Artist not found or already deleted.')], 404);
            }

            // Ensure no related artworks exist before deleting
            if ($artist->artworks()->exists()) {
                return response()->json(['error' => __('Cannot delete artist with associated artworks.')], 400);
            }

            // Delete artist's photo if it exists
            if ($artist->photo && \Storage::disk('public')->exists($artist->photo)) {
                \Storage::disk('public')->delete($artist->photo);
            }

            // Force delete the artist
            $artist->forceDelete();

            return response()->json(['success' => __('Artist deleted permanently.')], 200);

        } catch (\Exception $e) {
            \Log::error('Error in forceDelete: ' . $e->getMessage());
            return response()->json(['error' => __('Error deleting artist permanently.')], 500);
        }
    }
}
