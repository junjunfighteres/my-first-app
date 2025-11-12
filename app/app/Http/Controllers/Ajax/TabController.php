<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class TabController extends Controller
{
    public function show($type)
    {
        $user = Auth::user();

        switch ($type) {
            case 'joined':
                $events = $user->applications()
                    ->with('event')
                    ->whereHas('event', fn($q) => $q->where('del_flg', 0))
                    ->get()
                    ->pluck('event');
                break;

            case 'bookmarked':
                $events = $user->bookmarks()
                    ->with('event')
                    ->whereHas('event', fn($q) => $q->where('del_flg', 0))
                    ->get()
                    ->pluck('event');
                break;

            case 'hosted':
                // ✅ 修正版：ログインユーザー自身が作成したイベントのみ
                $events = Event::where('user_id', $user->id)
                    ->where('del_flg', 0)
                    ->orderBy('date', 'desc')
                    ->get();
                break;

            default:
                $events = Event::where('del_flg', 0)
                    ->orderBy('date', 'desc')
                    ->get();
        }

        // Bladeでイベントカードを描画
        return view('layouts.event_cards', compact('events'));
    }
}