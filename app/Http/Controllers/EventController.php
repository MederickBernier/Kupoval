<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function show($id){
        $event = Event::findOrFail($id);
        return view('public.event.show',[
            'event' => $event,
        ]);
    }
}
