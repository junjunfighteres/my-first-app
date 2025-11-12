<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Application;

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

        return view('user.applications.apply', compact('event', 'user'));
    }

    // ② 確認画面
    public function applyConfirm(Request $request)
    {
        $eventId = $request->input('event_id');
        $event = Event::where('del_flg', 0)->findOrFail($eventId);

        // コメントは任意
        $validated = $request->validate([
            'comment' => ['nullable','string','max:1000'],
        ]);

        // 確認画面に引き継ぐ
        return view('user.applications.apply_confirm', [
            'event'   => $event,
            'comment' => $validated['comment'] ?? '',
        ]);
    }

    // ③ 完了（保存）
    public function applyComplete(Request $request)
    {
        $eventId = $request->input('event_id');
        $event = Event::where('del_flg', 0)->findOrFail($eventId);

        // 本番では auth 必須にすること推奨
        $user = Auth::user();
        if (!$user) {
            // 未ログインならログインへ（暫定）
            return redirect()->route('login')->with('error','ログインが必要です。');
        }

        $validated = $request->validate([
            'comment' => ['nullable','string','max:1000'],
        ]);

        // 二重申込防止（同一 user_id × event_id を1件に）
        Application::firstOrCreate(
            [
                'user_id'  => $user->id,
                'event_id' => $event->id,
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
}