<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BioController extends Controller
{
    /**
     * Display the list of artists.
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
     * Display a specific artist's biography.
     */
    public function show(Artist $artist)
    {
        try {
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
