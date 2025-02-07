<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function show(Event $event)
    {
        try {
            return view('public.event.show', compact('event'));
        } catch (\Exception $e) {
            throwError('Failed to load event details', 500, ['details' => $e->getMessage()]);
        }
    }
}
