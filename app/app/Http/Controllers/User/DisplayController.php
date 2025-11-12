<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class DisplayController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $type = $request->query('type', 'all');

        if ($type === 'joined') {
            // 参加済みイベント
            $events = Event::whereIn('id', function ($query) use ($user) {
                $query->select('event_id')
                      ->from('applications')
                      ->where('user_id', $user->id);
            })->where('del_flg', 0)->get();

        } elseif ($type === 'bookmarked') {
            // ブックマークしたイベント
            $events = Event::whereIn('id', function ($query) use ($user) {
                $query->select('event_id')
                      ->from('bookmarks')
                      ->where('user_id', $user->id);
            })->where('del_flg', 0)->get();

        } elseif ($type === 'hosted') {
            // 主催イベント（role=1 のユーザーのみ表示）
            $events = Event::where('user_id', $user->id)
                ->where('del_flg', 0)
                ->when($user->role != 1, function ($query) {
                    $query->whereRaw('1=0'); // role=1 以外は空
                })
                ->get();

        } else {
            // すべてのイベント
            $events = Event::where('del_flg', 0)->get();
        }

        return view('user.main', compact('events'));
    }
}