<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;

class BioController extends Controller
{
    public function index(){
        try {
            $artists = Artist::all(); // Fetch all artists

            if($artists->count() === 1){
                return redirect()->route('bio.show', ['artist' => $artists->first()]);
            }

            return view('public.bio.index', [
                'artists' => $artists // Fix: Ensure 'artists' is passed to the view
            ]);

        } catch (\Exception $e) {
            throwError('Failed to load bio page', 500, ['details' => $e->getMessage()]);
        }
    }

    public function show(Artist $artist)
    {
        try {
            return view('public.bio.show', compact('artist'));
        } catch (\Exception $e) {
            throwError('Failed to load artist page', 500, ['details' => $e->getMessage()]);
        }
    }
}
