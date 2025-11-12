<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Application;
use App\Models\Bookmark;

class TabController extends Controller
{
    public function show($type)
    {
        $user = Auth::user();

        switch ($type) {
            case 'joined': // 参加済みイベント
                $eventIds = Application::where('user_id', $user->id)->pluck('event_id');
                $events = Event::whereIn('id', $eventIds)->where('del_flg', 0)->get();
                break;

            case 'bookmarked': // ブックマークしたイベント
                $eventIds = Bookmark::where('user_id', $user->id)->pluck('event_id');
                $events = Event::whereIn('id', $eventIds)->where('del_flg', 0)->get();
                break;

            case 'hosted': // 主催イベント
                // ✅ 修正版：ログインユーザー自身が作成したイベントのみ
                $events = Event::where('user_id', $user->id)
                    ->where('del_flg', 0)
                    ->orderBy('date', 'desc')
                    ->get();
                break;

            default:
                $events = Event::where('del_flg', 0)->get();
                break;
        }

        // Bladeでイベントカードを描画
        return view('layouts.event_cards', compact('events'));
    }
}