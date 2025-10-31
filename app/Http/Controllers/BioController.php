<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BioController extends Controller
{
    /**
     * Display a listing of the artists.
     *
     * This method retrieves all artists ordered by name in ascending order.
     * If no artists are found, it redirects to the home page with a warning message.
     * If only one artist is found, it redirects to the bio show page for that artist.
     * Otherwise, it returns the bio index view with the list of artists.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        try {
            $artists = Artist::orderBy('name', 'asc')->get();

            if ($artists->isEmpty()) {
                return redirect()->route('home')->with('warning', __('No artists found.'));
            }

            if ($artists->count() === 1) {
                return redirect()->route('bio.show', ['artist' => $artists->first()]);
            }

            return view('public.bio.index', compact('artists'));
        } catch (\Exception $e) {
            Log::error('❌ Failed to load bio page: ' . $e->getMessage());

            return redirect()->route('home')
                ->with('error', __('An error occurred while loading the bio page.'));
        }
    }

    /**
     * Display the specified artist's biography.
     *
     * @param \App\Models\Artist $artist The artist whose biography is to be displayed.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the artist is not found.
     * @throws \Exception If an error occurs while loading the artist page.
     */
    public function show(Artist $artist)
    {
        try {
            // Load the artist with their artworks for the profile display
            $artist->load(['artworks' => function($query) {
                $query->where('is_on_sale', true)
                      ->orderBy('is_featured', 'desc')
                      ->orderBy('created_at', 'desc');
            }]);
            
            return view('public.bio.show', compact('artist'));
        } catch (ModelNotFoundException $e) {
            Log::error('❌ Artist not found: ' . $e->getMessage());

            return redirect()->route('bio.index')
                ->with('error', __('The selected artist could not be found.'));
        } catch (\Exception $e) {
            Log::error('❌ Failed to load artist page: ' . $e->getMessage());

            return redirect()->route('bio.index')
                ->with('error', __('An error occurred while loading the artist page.'));
        }
    }
}
