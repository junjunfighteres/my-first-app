<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 基本条件（削除されていないイベント）
        $query = Event::where('del_flg', 0);

        // 🔍 キーワード検索（タイトル or 説明）
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

        // 💻 開催形式（Twitch / YouTube）
        if ($request->filled('platform')) {
            $query->where('format', $request->input('platform'));
        }

        // デフォルトは全イベント表示
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
    public function show(Event $event) {
    // 関連イベントを取得（同じ形式で、現在のイベント以外）
        $related = Event::where('format', $event->format)
            ->where('id', '!=', $event->id)
            ->where('del_flg', 0)
            ->limit(6)
            ->get();

        return view('user.events.detail', compact('event', 'related'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event) {
        if ($event->user_id !== Auth::id()) {
        abort(403);
        }

        return view('user.host.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function updateConfirm(Request $request) {
        $validated = $request->validate([
            'event_id'    => 'required|integer',
            'title'       => 'required|max:255',
            'date'        => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required',
            'format'      => 'required',
            'capacity'    => 'required|integer|min:1',
            'status'      => 'required',
            'description' => 'nullable|max:2000',
            'image'       => 'nullable|image|max:2048',
            'current_image' => 'nullable|string',
        ]);

        // 新しい画像があれば一時保存
        if ($request->hasFile('image')) {
            $temp = $request->file('image')->store('temp', 'public');
            $validated['image_path'] = $temp;
        } else {
            $validated['image_path'] = $validated['current_image'] ?? null;
        }

        return view('user.host.edit_confirm', ['data' => $validated]);
    }

    public function updateComplete(Request $request) {
        $data = $request->all();
        $event = Event::findOrFail($data['event_id']);

        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        // 画像処理
        $finalImage = $event->image_path;

        if (!empty($data['image_path']) && str_contains($data['image_path'], 'temp/')) {
            $new = str_replace('temp', 'events', $data['image_path']);
            \Storage::disk('public')->move($data['image_path'], $new);
            $finalImage = $new;
        }

        // DB更新
        $event->update([
            'title'       => $data['title'],
            'date'        => $data['date'],
            'start_time'  => $data['start_time'],
            'end_time'    => $data['end_time'],
            'format'      => $data['format'],
            'capacity'    => $data['capacity'],
            'status' => 'required|string|in:public,private',
            'status'      => $data['status'],
            'description' => $data['description'],
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
            'status'      => 'required', // 公開/非公開
            'description' => 'nullable|max:2000',
            'image'       => 'nullable|image|max:2048',
        ]);

        // 画像を一時保存（確認画面で必要）
        if ($request->hasFile('image')) {
            $tempPath = $request->file('image')->store('temp', 'public');
            $validated['image_path'] = $tempPath;
        }

        return view('user.host.create_confirm', [
            'data' => $validated
        ]);
    }


    /**
     * 19. 新規登録（完了）
     */
    public function storeComplete(Request $request)
    {
        $user = Auth::user();

    // 🔹 一般ユーザー（role=0）も作成可能にする場合はコメントアウト
    // if ($user->role != 1) {
    //     return redirect()->route('events.index')
    //         ->with('error', '主催者権限がありません。');
    // }

    // バリデーション
    $validated = $request->validate([
        'title'       => 'required|string|max:255',
        'date'        => 'required|date',
        'start_time'  => 'required',
        'end_time'    => 'required',
        'format'      => 'required|string|max:100',
        'capacity'    => 'required|integer|min:1',
        'status'      => 'required|string',
        'description' => 'nullable|string|max:2000',
        'image_path'  => 'nullable|string',
    ]);

    // 画像を正式フォルダに移動
    $finalImage = null;
    if (!empty($validated['image_path'])) {
        $finalImage = str_replace('temp', 'events', $validated['image_path']);
        \Storage::disk('public')->move($validated['image_path'], $finalImage);
    }

    // DBに保存
    $event = Event::create([
        'user_id'     => $user->id,
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
