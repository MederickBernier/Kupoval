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
    public function index(){
        $recentArtworks = Artwork::orderBy('created_at','desc')->take(5)->get();
        $events = Event::where('start_date','>=',now())->orderBy('start_date','asc')->take(3)->get();

        return view('public.home',[
            'carouselItems' => $recentArtworks,
            'events' => $events,
        ]);
    }

    public function about(){
        $page = StaticPage::where('slug','about')->firstOrFail();
        $artist = Artist::first();
        return view('public.about',[
            'page' => $page,
            'artist' => $artist,
        ]);
    }

    public function bio(){
        $artist = Artist::first();
        return view('public.bio',[
            'artist' => $artist
        ]);
    }

    public function contact(){
        $settings = Setting::whereIn('key', ['site_address','site_phone','site_email'])->pluck('value','key');
        return view('public.contact',[
            'settings' => $settings
        ]);
    }

    public function gallery(){
        return view('public.gallery');
    }

    public function events(){
        $events = Event::where('start_date','>=', now())
        ->orderBy('start_date','asc')
        ->get()
        ->groupBy(function($event){
            return Carbon::parse($event->start_date)->format('F Y');
        });

        return view('public.events',[
            'eventsByMonth' => $events
        ]);
    }
}
