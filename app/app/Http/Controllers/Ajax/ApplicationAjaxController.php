<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;

class ApplicationAjaxController extends Controller
{
    public function store(Request $request)
    {
        try {
            Application::firstOrCreate([
                'user_id'  => Auth::id(),
                'event_id' => $request->event_id,
            ]);
            return response()->json(['status' => 'joined']);
        } catch (\Throwable $e) {
            \Log::error('Application store error: '.$e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    public function destroy($eventId)
    {
        try {
            Application::where('user_id', Auth::id())
                ->where('event_id', $eventId)
                ->delete();

            return response()->json(['status' => 'canceled']);
        } catch (\Throwable $e) {
            \Log::error('Application delete error: '.$e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
}