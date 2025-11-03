<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\Application;
use App\Models\Report;
use App\Models\Bookmark;
use Illuminate\Support\Facades\DB;

class DisplayController extends Controller
{
    /**
     * イベント一覧画面表示
     */
    public function index()
    {
        // とりあえず全イベントを取得（あとでログインユーザー別やタブ別に切り替え可能）
        $events = Event::all();

        return view('user_main', [
            'events' => $events,
        ]);
    }

    /**
     * イベント検索処理（キーワードなど）
     */
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        // 仮の検索処理（あとで日付や形式など条件を追加予定）
        $query = Event::query();

        if (!empty($keyword)) {
            $query->where('title', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
        }

        $events = $query->get();

        return view('user_main', [
            'events' => $events,
            'message' => "検索キーワード: " . ($keyword ?: '未入力'),
        ]);
    }
}