<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Application;
use App\Models\Bookmark;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $applications = Application::all();

        return view('applications.index', compact('applications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function show(Application $application)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Application $application)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $application)
    {
        //
    }

    // ① 申込フォーム表示
    public function applyForm($eventId)
    {
        $event = Event::where('del_flg', 0)->findOrFail($eventId);

        $user = Auth::user(); // 未ログインなら null（後でauth必須にするならミドルウェアで）

        $isJoined = Application::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->exists();

        return view('user.applications.apply', compact('event', 'user', 'isJoined'));
    }

    // ② 確認画面
    public function applyConfirm(Request $request)
    {
        $request->validate([
            'event_id' => 'required|integer',
            'comment' => 'nullable|string|max:1000',
        ]);

        // コメントは任意
        $validated = $request->validate([
            'comment' => ['nullable','string','max:1000'],
        ]);

        $event = Event::where('del_flg', 0)->findOrFail($request->event_id);

        // 🔥 定員チェック（現在の参加者数）
        $currentCount = Application::where('event_id', $event->id)->count();

        if ($currentCount >= $event->capacity) {
            return redirect()
                ->route('events.apply', $event->id)
                ->with('error', 'このイベントは定員に達しています。');
        }

        return view('user.applications.apply_confirm', [
            'event' => $event,
            'comment' => $request->comment,
        ]);
    }

    // ③ 完了（保存）
    public function applyComplete(Request $request)
    {
        $user = Auth::user();
        $event = Event::where('del_flg', 0)->findOrFail($request->event_id);

        $currentCount = Application::where('event_id', $event->id)->count();
        if ($currentCount >= $event->capacity) {
            return redirect()
                ->route('events.apply', $event->id)
                ->with('error', 'このイベントは定員に達しているため、参加できませんでした。');
        }

        $validated = $request->validate([
            'comment' => ['nullable','string','max:1000'],
        ]);

        // 二重申込防止（同一 user_id × event_id を1件に）
        Application::firstOrCreate(
            [   
                'user_id'  => $user->id,
                'event_id' => $event->id,
                'comment' => $request->comment ?? '',
            ],
            [   
                'comment'  => $validated['comment'] ?? '',
            ]
        );

        // 完了画面へ
        return view('user.applications.apply_complete', [
            'event'   => $event,
            'comment' => $validated['comment'] ?? '',
        ]);
    }

    /**
     * 参加キャンセル処理
     */
    public function cancel(Event $event)
    {
        $user = Auth::user();

        Application::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->delete();

        return redirect()->route('events.show', $event->id)
            ->with('success', 'イベントの参加をキャンセルしました。');
    }
}