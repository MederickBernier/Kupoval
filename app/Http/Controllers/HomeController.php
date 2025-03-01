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
     *
     * This method loads featured and recent artworks, as well as upcoming events,
     * and passes them to the 'public.home' view. If a database error occurs, it logs
     * the error and redirects to the home page with an error message. If any other
     * exception occurs, it logs the error and redirects to the home page with a generic
     * error message.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        try {
            Log::info("🔹 Loading homepage data");

            $featuredArtworks = Artwork::where('is_featured', true)->latest()->take(6)->get();

            $remainingSlots = 6 - $featuredArtworks->count();
            $recentArtworks = ($remainingSlots > 0)
                ? $featuredArtworks->merge(Artwork::where('is_featured', false)->latest()->take($remainingSlots)->get())
                : $featuredArtworks;

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
     *
     * This method loads the static content for the About page and the first artist record,
     * then passes them to the 'public.about' view. If an exception occurs, it logs the error
     * and redirects to the home page with an error message.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function about()
    {
        try {
            Log::info("🔹 Loading About page");

            $locale = app()->getLocale();
            $page = StaticPage::where('slug', 'about')->first();
            $artist = Artist::first();

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
     * Display the contact page.
     *
     * This method attempts to load the contact page by retrieving site settings
     * such as address, phone, and email from the database. If successful, it
     * returns the contact view with the settings. If a database error occurs,
     * it logs the error and redirects to the home page with an error message.
     * If any other exception occurs, it logs the error and redirects to the
     * home page with a generic error message.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
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
     * Display the gallery page.
     *
     * This method attempts to load the gallery page view. If an exception occurs during the process,
     * it logs the error and redirects the user to the home page with an error message.
     *
     * @return \Illuminate\Http\Response
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
     * Display a listing of upcoming events grouped by month and year.
     *
     * This method retrieves events from the database that have a start date
     * greater than or equal to the current date. The events are then grouped
     * by their start date's month and year, and passed to the 'public.events' view.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     *         Returns the events view if successful, or redirects to the home page
     *         with an error message if an exception occurs.
     *
     * @throws \Illuminate\Database\QueryException
     *         If a database error occurs while retrieving the events.
     * @throws \Exception
     *         If any other error occurs while loading the events page.
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
