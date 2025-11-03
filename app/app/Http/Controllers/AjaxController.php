<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjaxController extends Controller
{
    public function fetchEventsByType($type)
    {
        $user = Auth::user();

        switch ($type) {
            case 'joined':
                $events = $user->joinedEvents;
                break;
            case 'bookmarked':
                $events = $user->bookmarkedEvents;
                break;
            case 'hosted':
                $events = $user->hostedEvents;
                break;
            default:
                $events = collect();
        }

        return view('partials.event_cards', compact('events'));
    }
}