<?php

namespace App\Http\Controllers;

use App\Mail\ContactArtistMail;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ContactArtistController extends Controller
{
    /**
     * Display the contact form for an artist.
     */
    public function form(Artist $artist)
    {
        try {
            if (!$artist->email) {
                Log::warning("⚠️ Attempt to access contact form for artist without email: ID {$artist->id}");
                return redirect()->route('home')->with('warning', __('public/contact.artist_not_found'));
            }

            return view('public.artist.contact_artist', compact('artist'));
        } catch (\Exception $e) {
            Log::error("❌ Failed to load artist contact form: " . $e->getMessage());
            return redirect()->route('home')->with('error', __('public/contact.error_loading_form'));
        }
    }

    /**
     * Handle contact form submission and send an email to the artist.
     */
    public function send(Request $request)
    {
        try {
            // ✅ Validate input
            $validated = $request->validate([
                'artist_id' => 'required|exists:artists,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'subject' => 'nullable|string|max:255',
                'message' => 'required|string|min:10',
            ]);

            // ✅ Retrieve artist
            $artist = Artist::findOrFail($validated['artist_id']);

            if (!$artist->email) {
                Log::warning("⚠️ Contact attempt for artist without email: ID {$artist->id}");
                return redirect()->back()->with('warning', __('public/contact.artist_not_found'));
            }

            // ✅ Send email
            Mail::to($artist->email)->send(new ContactArtistMail($validated));

            Log::info("✅ Contact email successfully sent to artist ID {$artist->id} ({$artist->email})");

            return redirect()->back()->with('success', __('public/contact.success_message'));
        } catch (ValidationException $e) {
            // ✅ Handle validation errors
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error("❌ Failed to send contact email to artist: " . $e->getMessage());
            return redirect()->back()->with('error', __('public/contact.error_message'));
        }
    }
}
