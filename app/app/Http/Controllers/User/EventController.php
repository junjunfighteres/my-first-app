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
    public function index()
    {
        $events = Event::all();

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
        //
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
        $data = $request->all();

        // 画像を正式保管
        $finalImage = null;
        if (!empty($data['image_path'])) {
            $finalImage = str_replace('temp', 'events', $data['image_path']);
            \Storage::disk('public')->move($data['image_path'], $finalImage);
        }

        // DB保存
        $event = Event::create([
            'user_id'     => Auth::id(),
            'title'       => $data['title'],
            'date'        => $data['date'],
            'start_time'  => $data['start_time'],
            'end_time'    => $data['end_time'],
            'format'      => $data['format'],
            'capacity'    => $data['capacity'],
            'status'      => $data['status'],
            'description' => $data['description'] ?? null,
            'image_path'  => $finalImage,
            'del_flg'     => 0
        ]);

        return view('user.host.create_complete', compact('event'));
    }
}
