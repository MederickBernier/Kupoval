<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artwork;
use App\Models\Event;
use App\Models\Setting;
use App\Models\StaticPage;
use App\Models\Artist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index()
    {
        try {
            // Get featured artworks first (priority)
            $featuredArtworks = Artwork::where('is_featured', true)
                ->latest()
                ->take(6)
                ->get();

            // If there aren't enough featured artworks, fetch more from non-featured ones
            $remainingSlots = 6 - $featuredArtworks->count();

            if ($remainingSlots > 0) {
                $nonFeaturedArtworks = Artwork::where('is_featured', false)
                    ->latest()
                    ->take($remainingSlots)
                    ->get();

                // Merge both collections
                $recentArtworks = $featuredArtworks->merge($nonFeaturedArtworks);
            } else {
                $recentArtworks = $featuredArtworks;
            }

            // Get upcoming events
            $events = Event::where('start_date', '>=', now())
                ->orderBy('start_date')
                ->take(3)
                ->get();

            return view('public.home', compact('recentArtworks', 'events'));
        } catch (\Exception $e) {
            Log::error('Failed to load home page: ' . $e->getMessage());
            return redirect()->route('home')->with('error', __('Failed to load home page.'));
        }
    }

    /**
     * Display the About page.
     */
    public function about()
    {
        try {
            $page = StaticPage::where('slug', 'about')->first();
            $artist = Artist::first();

            if (!$page) {
                return redirect()->route('home')->with('error', __('About page not found.'));
            }

            return view('public.about', compact('page', 'artist'));
        } catch (\Exception $e) {
            Log::error('Failed to load about page: ' . $e->getMessage());
            return redirect()->route('home')->with('error', __('Failed to load about page.'));
        }
    }

    /**
     * Display the Contact page.
     */
    public function contact()
    {
        try {
            $settings = Setting::whereIn('key', ['site_address', 'site_phone', 'site_email'])
                ->pluck('value', 'key');

            return view('public.contact', compact('settings'));
        } catch (\Exception $e) {
            Log::error('Failed to load contact page: ' . $e->getMessage());
            return redirect()->route('home')->with('error', __('Failed to load contact page.'));
        }
    }

    /**
     * Display the Gallery page.
     */
    public function gallery()
    {
        try {
            return view('public.gallery');
        } catch (\Exception $e) {
            Log::error('Failed to load gallery page: ' . $e->getMessage());
            return redirect()->route('home')->with('error', __('Failed to load gallery page.'));
        }
    }

    /**
     * Display the Events page.
     */
    public function events()
    {
        try {
            $events = Event::where('start_date', '>=', now())
                ->orderBy('start_date')
                ->get()
                ->groupBy(fn($event) => Carbon::parse($event->start_date)->format('F Y'));

            return view('public.events', compact('events'));
        } catch (\Exception $e) {
            Log::error('Failed to load events page: ' . $e->getMessage());
            return redirect()->route('home')->with('error', __('Failed to load events page.'));
        }
    }
}
