<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artwork;
use App\Models\Event;

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
        return view('public.about');
    }

    public function bio(){
        return view('public.bio');
    }

    public function contact(){
        return view('public.contact');
    }

    public function gallery(){
        return view('public.gallery');
    }

    public function events(){
        return view('public.events');
    }
}
