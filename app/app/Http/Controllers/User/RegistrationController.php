<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function fetchByType($type)
    {
        $events = Event::where('type', $type)->get();
        return view('events.index',
        [
            'events' => $events,
            'type' => $type,
        ]);
    }
}
