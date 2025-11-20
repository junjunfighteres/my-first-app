<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Report;
use App\Models\Application;

class AdminEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // イベント管理一覧
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $userCount = User::count();
        $eventCount = Event::count();
        $reportCount = Report::count();
        $joinCount   = Application::count();

        // 一覧データ
        $events = Event::with(['user','applications'])
            ->withCount('applications')   // ← 参加人数を取得
            ->withCount('reports')        // ← 違反数（reports リレーション前提）
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('title', 'LIKE', "%{$keyword}%")
                      ->orWhereHas('user', function ($q) use ($keyword) {
                          $q->where('name', 'LIKE', "%{$keyword}%");
                      });
            })
            ->orderByDesc('reports_count')  // 違反が多い順
            ->orderByDesc('updated_at')     // 同数なら更新日時順
            ->limit(10)
            ->get();

        return view('admin.events.index', compact(
            'events',
            'keyword',
            'userCount',
            'eventCount',
            'reportCount',
            'joinCount'
        ));
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
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // 対象イベントを取得
        $event = Event::with(['user', 'applications.user', 'reports.user'])
            ->findOrFail($id);

        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
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

    public function hiddenConfirm(Event $event)
    {
        // 必要ならここでも権限チェック（adminミドルウェアでだいたいOK）
        return view('admin.events.hidden_confirm', compact('event'));
    }

    /**
     * 26. 非表示完了処理
     */
    public function hiddenComplete(Request $request, Event $event)
    {
        // del_flg=1 で「非表示扱い」にする（既に削除もこの運用なので合わせる）
        $event->update([
            'status' => 'hidden'
        ]);

        return view('admin.events.hidden_complete', compact('event'));
    }

}
