<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $carouselItems = [
            ['image' => 'https://picsum.photos/seed/picsum/800/400', 'title' => 'Artwork 1', 'description' => 'Description 1'],
            ['image' => 'https://picsum.photos/seed/forest/800/400', 'title' => 'Artwork 2', 'description' => 'Description 2'],
            ['image' => 'https://picsum.photos/seed/water/800/400', 'title' => 'Artwork 3', 'description' => 'Description 3'],
        ];

        $events = [
            // [
            //     'title' => 'Art & Fantasy Exhibition',
            //     'description' => 'An exclusive collection of artworks blending fantasy and reality.',
            //     'location' => 'Art Center, Montreal',
            //     'date' => '2025-02-15',
            // ],
            // [
            //     'title' => 'Kupoval Open Market',
            //     'description' => 'Discover unique and exclusive pieces directly from the artist.',
            //     'location' => 'Downtown Market, Quebec City',
            //     'date' => '2025-03-05',
            // ],
            // [
            //     'title' => 'Evening with Kupoval',
            //     'description' => 'A special evening to meet the artist and explore her inspiration.',
            //     'location' => 'Art Gallery, Toronto',
            //     'date' => '2025-04-10',
            // ],
            // [
            //     'title' => 'Fantasy Fair Showcase',
            //     'description' => 'Kupoval presents a new fantasy-themed collection.',
            //     'location' => 'Convention Center, Vancouver',
            //     'date' => '2025-05-20',
            // ],
            // [
            //     'title' => 'Kupoval Charity Auction',
            //     'description' => 'Participate in an auction for a good cause and own exclusive art pieces.',
            //     'location' => 'Cultural Hall, Ottawa',
            //     'date' => '2025-06-30',
            // ],
        ];

        return view('public.home',[
            'carouselItems' => $carouselItems,
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
