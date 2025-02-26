<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artwork;
use App\Models\Event;
use App\Models\Setting;
use App\Models\StaticPage;
use App\Models\Artist;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Exception;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index()
    {
        try {
            Log::info("🔹 Loading homepage data");

            // ✅ Get featured artworks
            $featuredArtworks = Artwork::where('is_featured', true)->latest()->take(6)->get();

            // ✅ Fill missing slots with non-featured artworks
            $remainingSlots = 6 - $featuredArtworks->count();
            $recentArtworks = ($remainingSlots > 0)
                ? $featuredArtworks->merge(Artwork::where('is_featured', false)->latest()->take($remainingSlots)->get())
                : $featuredArtworks;

            // ✅ Get upcoming events
            $events = Event::where('start_date', '>=', now())->orderBy('start_date')->take(3)->get();

            Log::info("✅ Homepage data loaded successfully");

            return view('public.home', compact('recentArtworks', 'events'));
        } catch (QueryException $e) {
            Log::error("❌ Database error on homepage: " . $e->getMessage());
            return redirect()->route('home')->with('error', __('Database error while loading the homepage.'));
        } catch (Exception $e) {
            Log::error("❌ Failed to load homepage: " . $e->getMessage());
            return redirect()->route('home')->with('error', __('An unexpected error occurred while loading the homepage.'));
        }
    }

    /**
     * Display the About page.
     */
    public function about()
    {
        try {
            Log::info("🔹 Loading About page");

            $locale = app()->getLocale(); // Get current language (e.g., 'enca', 'frca')
            $page = StaticPage::where('slug', 'about')->first();
            $artist = Artist::first();

            // Use helper to extract content dynamically
            $pageData = extractStaticContent($page, $locale);

            Log::info("✅ About page loaded successfully");

            return view('public.about', [
                'page' => (object) $pageData,
                'artist' => $artist,
            ]);
        } catch (\Exception $e) {
            Log::error("❌ Failed to load About page: " . $e->getMessage());
            return redirect()->route('home')->with('error', __('public/interface.unexpected_error_about'));
        }
    }

    /**
     * Display the Contact page.
     */
    public function contact()
    {
        try {
            Log::info("🔹 Loading Contact page");

            $settings = Setting::whereIn('key', ['site_address', 'site_phone', 'site_email'])
                ->pluck('value', 'key');

            Log::info("✅ Contact page loaded successfully");

            return view('public.contact', compact('settings'));
        } catch (QueryException $e) {
            Log::error("❌ Database error on Contact page: " . $e->getMessage());
            return redirect()->route('home')->with('error', __('Database error while loading the Contact page.'));
        } catch (Exception $e) {
            Log::error("❌ Failed to load Contact page: " . $e->getMessage());
            return redirect()->route('home')->with('error', __('An unexpected error occurred while loading the Contact page.'));
        }
    }

    /**
     * Display the Gallery page.
     */
    public function gallery()
    {
        try {
            Log::info("🔹 Loading Gallery page");

            return view('public.gallery');
        } catch (Exception $e) {
            Log::error("❌ Failed to load Gallery page: " . $e->getMessage());
            return redirect()->route('home')->with('error', __('An error occurred while loading the Gallery page.'));
        }
    }

    /**
     * Display the Events page.
     */
    public function events()
    {
        try {
            Log::info("🔹 Loading Events page");

            $events = Event::where('start_date', '>=', now())
                ->orderBy('start_date')
                ->get()
                ->groupBy(fn($event) => Carbon::parse($event->start_date)->format('F Y'));

            Log::info("✅ Events page loaded successfully");

            return view('public.events', compact('events'));
        } catch (QueryException $e) {
            Log::error("❌ Database error on Events page: " . $e->getMessage());
            return redirect()->route('home')->with('error', __('Database error while loading the Events page.'));
        } catch (Exception $e) {
            Log::error("❌ Failed to load Events page: " . $e->getMessage());
            return redirect()->route('home')->with('error', __('An unexpected error occurred while loading the Events page.'));
        }
    }
}
