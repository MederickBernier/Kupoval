<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return view('public.home');
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
