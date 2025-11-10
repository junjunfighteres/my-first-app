<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class TabController extends Controller
{
    public function show($type)
    {
        $user = Auth::user();

        // 未ログインの場合は空を返す
        if (!$user) {
            return response()->view('layouts.event_cards', ['events' => collect([])]);
        }

        switch ($type) {
            case 'joined':
                $events = Event::whereIn('id', function ($query) use ($user) {
                    $query->select('event_id')
                          ->from('applications')
                          ->where('user_id', $user->id);
                })->where('del_flg', 0)->get();
                break;

            case 'bookmarked':
                $events = Event::whereIn('id', function ($query) use ($user) {
                    $query->select('event_id')
                          ->from('bookmarks')
                          ->where('user_id', $user->id);
                })->where('del_flg', 0)->get();
                break;

            case 'hosted':
                // 主催イベントはログインユーザーIDで絞る！
                $events = Event::where('user_id', $user->id)
                    ->where('del_flg', 0)
                    ->get();
                break;

            default:
                $events = Event::where('del_flg', 0)->get();
                break;
        }

        // 部分テンプレートを返す
        return view('layouts.event_cards', compact('events'));
    }
}