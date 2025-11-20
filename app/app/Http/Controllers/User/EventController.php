<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Event;
use App\Models\Application;
use App\Models\Bookmark;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    $user = Auth::user();

    // 基本条件（削除されておらず、公開 or 自分のイベント）
    $query = Event::where('del_flg', 0)
        ->where(function ($q) use ($user) {
            $q->where('status', 'public')
              ->orWhere('user_id', $user->id); // ← 自分の非公開はOK
        });

    // 🔍 キーワード検索
    if ($request->filled('keyword')) {
        $keyword = $request->input('keyword');
        $query->where(function($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%");
        });
    }

    // 📅 日付検索
    if ($request->filled('start_date')) {
        $query->where('date', '>=', $request->input('start_date'));
    }
    if ($request->filled('end_date')) {
        $query->where('date', '<=', $request->input('end_date'));
    }

    // 💻 プラットフォーム検索
    if ($request->filled('platform')) {
        $query->where('format', $request->input('platform'));
    }

    // 並び替え
    $events = $query->orderBy('date', 'asc')->get();

    return view('user.main', compact('events'));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
{
    // ⭐ 非公開イベントは主催者以外見れない
    if ($event->status === 'private' && $event->user_id !== Auth::id()) {
        abort(404); // 存在しない扱いにする方が安全
    }

    // 主催者情報をロード
    $event->load('user');

    // ⭐ 関連イベント（主催者は非公開も見える）
    $related = Event::where('format', $event->format)
        ->where('id', '!=', $event->id)
        ->where('del_flg', 0)
        ->where(function ($q) {
            $q->where('status', 'public')
              ->orWhere('user_id', Auth::id()); // ← 自分の非公開は見える
        })
        ->limit(6)
        ->get();

    // コメント取得
    $comments = $event->applications()
        ->with('user')
        ->orderBy('created_at', 'desc')
        ->get();
    
    // 参加状態
    $isJoined = Auth::check() && Application::where('user_id', Auth::id())
        ->where('event_id', $event->id)
        ->exists();

    // ブックマーク状態
    $isBookmarked = Auth::check() && Bookmark::where('user_id', Auth::id())
        ->where('event_id', $event->id)
        ->exists();

    return view('user.events.detail', compact(
        'event',
        'related',
        'isJoined',
        'comments',
        'isBookmarked'
    ));
}

    public function showHost(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        // host用の表示
        return view('user.host.detail', compact('event'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    /**
     * 編集画面
     */
    public function edit(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.host.edit', compact('event'));
    }

    /**
     * 編集内容確認
     */
    public function updateConfirm(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'event_id'      => 'required|integer',
            'title'         => 'required|max:255',
            'date'          => 'required|date',
            'start_time'    => 'required',
            'end_time'      => 'required',
            'format'        => 'required',
            'capacity'      => 'required|integer|min:1',
            'status'        => 'required|string|in:public,private',
            'description'   => 'nullable|max:2000',
            'image'         => 'nullable|image|max:10240',
            'current_image' => 'nullable|string',
        ]);

        // 新しい画像 → temp へ保存
        if ($request->hasFile('image')) {
            $tempPath = $request->file('image')->store('temp_events', 'public');
            $validated['image_path'] = $tempPath;
        } else {
            $validated['image_path'] = $validated['current_image'] ?? null;
        }

        return view('user.host.edit_confirm', [
            'data' => $validated
        ]);
    }

    /**
     * 編集完了
     */
    public function updateComplete(Request $request)
    {
        // バリデ再チェック（改ざん防止）
        $validated = $request->validate([
            'event_id'    => 'required|integer|exists:events,id',
            'title'       => 'required|max:255',
            'date'        => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required',
            'format'      => 'required',
            'capacity'    => 'required|integer|min:1',
            'status'      => 'required|string|in:public,private',
            'description' => 'nullable|max:2000',
            'image_path'  => 'nullable|string',
        ]);

        $event = Event::findOrFail($validated['event_id']);

        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        // 現在の画像
        $finalImage = $event->image_path;

        // temp_events にある → 新しい画像なので移動
        if ($validated['image_path'] && strpos($validated['image_path'], 'temp_events') === 0) {
            $newPath = str_replace('temp_events', 'events', $validated['image_path']);
            Storage::disk('public')->move($validated['image_path'], $newPath);
            $finalImage = $newPath;
        }

        // DB 更新
        $event->update([
            'title'       => $validated['title'],
            'date'        => $validated['date'],
            'start_time'  => $validated['start_time'],
            'end_time'    => $validated['end_time'],
            'format'      => $validated['format'],
            'capacity'    => $validated['capacity'],
            'status'      => $validated['status'],
            'description' => $validated['description'],
            'image_path'  => $finalImage,
        ]);

        return view('user.host.edit_complete', compact('event'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        // 権限チェック（主催者本人のみ）
        if (Auth::id() !== $event->user_id) {
            abort(403, 'このイベントを削除する権限がありません。');
        }

        // 実際の削除処理（物理削除 or 論理削除）
        // 論理削除を使いたいなら下をコメントアウトして del_flg を更新する形でもOK
        // $event->delete();
        $event->update(['del_flg' => 1]);

        // メッセージ付きで一覧にリダイレクト
        return redirect()->route('events.index')->with('success', 'イベントを削除しました。');
    }

    //新規イベント作成画面表示
    public function create()
    {
        return view('user.host.create');
    }

    //作成内容確認
    public function storeConfirm(Request $request)
{
    $validated = $request->validate([
        'title'       => 'required|max:255',
        'date'        => 'required|date',
        'start_time'  => 'required',
        'end_time'    => 'required',
        'format'      => 'required',
        'capacity'    => 'required|integer|min:1',
        'status'      => 'required',
        'description' => 'nullable|max:2000',
        'image'       => 'nullable|image|max:10240', //10MB
    ]);

    $data = $request->only([
        'title','date','start_time','end_time',
        'format','capacity','status','description'
    ]);

    // ⭐ 一時保存
    $tempPath = null;
    if ($request->hasFile('image')) {
        $tempPath = $request->file('image')->store('temp_events', 'public');
    }

    return view('user.host.create_confirm', [
        'data'     => $data,
        'tempPath' => $tempPath,
    ]);
}

    /**
     * 19. 新規登録（完了）
     */
    public function storeComplete(Request $request)
{
    $validated = $request->validate([
        'title'       => 'required|string|max:255',
        'date'        => 'required|date',
        'start_time'  => 'required',
        'end_time'    => 'required',
        'format'      => 'required|string|max:100',
        'capacity'    => 'required|integer|min:1',
        'status'      => 'required|string',
        'description' => 'nullable|string|max:2000',
        'temp_image'  => 'nullable|string', // ← ココ重要！
    ]);

    // ⭐ 本保存
    $finalImage = null;
    if (!empty($validated['temp_image'])) {
        $finalImage = str_replace('temp_events', 'events', $validated['temp_image']);
        Storage::disk('public')->move($validated['temp_image'], $finalImage);
    }

    $event = Event::create([
        'user_id'     => Auth::id(),
        'title'       => $validated['title'],
        'date'        => $validated['date'],
        'start_time'  => $validated['start_time'],
        'end_time'    => $validated['end_time'],
        'format'      => $validated['format'],
        'capacity'    => $validated['capacity'],
        'status'      => $validated['status'],
        'description' => $validated['description'] ?? null,
        'image_path'  => $finalImage,
        'del_flg'     => 0,
    ]);

    return view('user.host.create_complete', compact('event'));
}
}
