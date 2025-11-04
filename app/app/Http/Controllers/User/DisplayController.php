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
    public function main(Request $request)
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

    public function eventDetail($id)
{
    // イベントモデルを読み込み
    $event = \App\Models\Event::find($id);
    $related = collect([
        (object)['title' => '関連イベントA'],
        (object)['title' => '関連イベントB'],
        (object)['title' => '関連イベントC'],
    ]);
    $comments = collect([
        (object)['user' => 'ユーザーA', 'content' => '楽しみです！'],
        (object)['user' => 'ユーザーB', 'content' => 'よろしくお願いします！']
    ]);

    // データが存在しない場合は404を返す
    if (!$event) {
        abort(404, 'イベントが見つかりません');
    }

    // user_event_detail.blade.php にデータを渡す
    return view('user.user_event_detail', compact('event', 'related', 'comments'));
}
}