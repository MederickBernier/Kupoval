<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artwork;
use App\Models\Event;
use App\Models\Setting;
use App\Models\StaticPage;
use App\Models\Artist;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $recentArtworks = Artwork::orderBy('created_at', 'desc')->take(5)->get();
            $events = Event::where('start_date', '>=', now())
                ->orderBy('start_date', 'asc')
                ->take(3)
                ->get();

            return view('public.home', [
                'carouselItems' => $recentArtworks,
                'events' => $events,
            ]);
        } catch (\Exception $e) {
            throwError('Failed to load home page data', 500, ['details' => $e->getMessage()]);
        }
    }

    public function about()
    {
        try {
            $page = StaticPage::where('slug', 'about')->firstOrFail();
            $artist = Artist::first();

            return view('public.about', [
                'page' => $page,
                'artist' => $artist,
            ]);
        } catch (\Exception $e) {
            throwError('Failed to load about page', 500, ['details' => $e->getMessage()]);
        }
    }

    public function bio()
    {
        try {
            $artist = Artist::first();

            return view('public.bio', [
                'artist' => $artist,
            ]);
        } catch (\Exception $e) {
            throwError('Failed to load bio page', 500, ['details' => $e->getMessage()]);
        }
    }

    public function contact()
    {
        try {
            $settings = Setting::whereIn('key', ['site_address', 'site_phone', 'site_email'])
                ->pluck('value', 'key');

            return view('public.contact', [
                'settings' => $settings,
            ]);
        } catch (\Exception $e) {
            throwError('Failed to load contact page', 500, ['details' => $e->getMessage()]);
        }
    }

    public function gallery()
    {
        try {
            return view('public.gallery');
        } catch (\Exception $e) {
            throwError('Failed to load gallery page', 500, ['details' => $e->getMessage()]);
        }
    }

    public function events()
    {
        try {
            $events = Event::where('start_date', '>=', now())
                ->orderBy('start_date', 'asc')
                ->get()
                ->groupBy(function ($event) {
                    return Carbon::parse($event->start_date)->format('F Y');
                });

            return view('public.events', [
                'eventsByMonth' => $events,
            ]);
        } catch (\Exception $e) {
            throwError('Failed to load events page', 500, ['details' => $e->getMessage()]);
        }
    }
}
