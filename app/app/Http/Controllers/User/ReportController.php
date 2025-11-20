<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Report;

class ReportController extends Controller
{
    // 報告フォーム
    public function create(Event $event)
    {
        return view('user.report.create', compact('event'));
    }

    // 報告の保存
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'reason' => 'required|max:1000',
        ]);

        Report::create([
            'user_id'  => Auth::id(),
            'event_id' => $event->id,
            'reason'   => $request->reason,
        ]);

        return redirect()->route('report.complete');
    }

    // 完了画面
    public function complete()
    {
        return view('user.report.complete');
    }
}