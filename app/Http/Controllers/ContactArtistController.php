<?php

namespace App\Http\Controllers;

use App\Mail\ContactArtistMail;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactArtistController extends Controller
{

    public function form(Artist $artist)
    {
        if (!$artist->email) {
            return redirect()->route('home')->with('error', __('public/contact.artist_not_found'));
        }

        return view('public.artist.contact_artist', compact('artist'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        $artist = Artist::findOrFail($validated['artist_id']);

        if (!$artist->email) {
            return redirect()->back()->with('error', __('public/contact.artist_not_found'));
        }

        try {
            Mail::to($artist->email)->send(new ContactArtistMail($validated));
            return redirect()->back()->with('success', __('public/contact.success_message'));
        } catch (\Exception $e) {
            Log::error('Failed to send contact email: ' . $e->getMessage());
            return redirect()->back()->with('error', __('public/contact.error_message'));
        }
    }
}
