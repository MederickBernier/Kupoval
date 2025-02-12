<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artwork;

class ArtworkController extends Controller
{
    public function show(Artwork $artwork){
        return view('public.artwork.show', [
            'artwork' => $artwork
        ]);
    }
}
