<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bookmark;

class BookmarkAjaxController extends Controller
{
    /**
     * タブの種類に応じてイベント一覧を返す
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $type = $request->query('type', 'all');

        // デフォルト（すべてのイベント）
        $events = Event::where('del_flg', 0)->get();

        if ($type === 'joined') {
            // 参加済みイベント
            $events = Event::whereIn('id', Application::where('user_id', $user->id)->pluck('event_id'))
                           ->where('del_flg', 0)
                           ->get();
        }

        if ($type === 'bookmarked') {
            // ブックマーク済みイベント
            $events = Event::whereIn('id', Bookmark::where('user_id', $user->id)->pluck('event_id'))
                           ->where('del_flg', 0)
                           ->get();
        }

        if ($type === 'hosted') {
            // 自分が主催したイベント（role関係なし）
            $events = Event::where('user_id', $user->id)
                           ->where('del_flg', 0)
                           ->get();
        }

        // 部分テンプレート（layouts/event_cards.blade.php）を返す
        return view('layouts.event_cards', compact('events'))->render();
    }

    /**
     * ブックマーク追加
     */
    public function store(Request $request)
    {
        try {
            Bookmark::firstOrCreate([
                'user_id' => Auth::id(),
                'event_id' => $request->event_id,
            ]);

            return response()->json(['status' => 'bookmarked']);
        } catch (\Throwable $e) {
            \Log::error('Bookmark store error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * ブックマーク削除
     */
    public function destroy($eventId)
    {
        try {
            Bookmark::where('user_id', Auth::id())
                ->where('event_id', $eventId)
                ->delete();

            return response()->json(['status' => 'unbookmarked']);
        } catch (\Throwable $e) {
            \Log::error('Bookmark delete error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}