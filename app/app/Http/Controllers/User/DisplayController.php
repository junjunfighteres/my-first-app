<?php

namespace App\Http\Controllers\User;
use App\Models\User;
use App\Models\Event;
use App\Models\Application;
use App\Models\Report;
use App\Models\Bookmark;



use App\Http\Controllers\Controller;

class DisplayController extends Controller
{
    public function index()
    {
        $events = $events = Event::where('del_flg', 0)
            ->orderBy('date', 'asc')
            ->get();

        return view('user.main', ['events' => $events]);
    }
}