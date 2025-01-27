<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EventController extends Controller
{
    public function show($id){
        try{

        }catch(ModelNotFoundException $e){
            throwError('Event not found', 404,['event_id' => $id]);
        }catch(\Exception $e){
            throwError('Failed to load event details', 500,['details' => $e->getMessage()]);
        }
    }
}
