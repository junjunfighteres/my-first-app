<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    /**
     * 一覧表示
     */
    public function index(Request $request)
{
    $keyword = $request->input('keyword');

    $base = User::query()
        ->where('role', '!=', 2)
        ->where('del_flg', 0)

        // ① まずイベント数（必ず最初に）
        ->withCount('events')

        // 🔍 検索
        ->when($keyword, function ($q) use ($keyword) {
            $q->where(function ($sub) use ($keyword) {
                $sub->where('name', 'LIKE', "%$keyword%")
                    ->orWhere('email', 'LIKE', "%$keyword%");
            });
        });

    // ② total_reports_count を安全に取得
    $base->addSelect([
        'total_reports_count' => DB::table('events')
            ->leftJoin('reports', 'reports.event_id', '=', 'events.id')
            ->selectRaw('COUNT(reports.id)')
            ->whereColumn('events.user_id', 'users.id')
            ->groupBy('events.user_id')
    ]);

    // ③ 違反率
    $base->addSelect(DB::raw("
        CASE 
            WHEN events_count = 0 THEN 0
            ELSE total_reports_count / events_count
        END AS violation_rate
    "));

    // ④ 並び順
    $users = $base
        ->orderByDesc('violation_rate')
        ->orderByDesc('updated_at')
        ->paginate(20);

    return view('admin.users.index', compact('users', 'keyword'));
}
}