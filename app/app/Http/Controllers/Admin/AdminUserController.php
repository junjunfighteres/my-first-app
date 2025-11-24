<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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

    // ユーザー基本情報
    $query = User::query()
        ->where('role', '!=', 2);

    // 🔍 検索
    if (!empty($keyword)) {
        $query->where(function ($sub) use ($keyword) {
            $sub->where('name', 'LIKE', "%$keyword%")
                ->orWhere('email', 'LIKE', "%$keyword%");
        });
    }

    // ⭐ Laravel6 でも動く違反率ロジック（全部 addSelect 内で完結）
    $query->addSelect([
        // イベント数
        'events_count' => DB::table('events')
            ->selectRaw('COUNT(*)')
            ->whereColumn('events.user_id', 'users.id')
            ->where('events.del_flg', 0),

        // 違反報告数
        'total_reports_count' => DB::table('events')
            ->leftJoin('reports', 'reports.event_id', '=', 'events.id')
            ->selectRaw('COUNT(reports.id)')
            ->whereColumn('events.user_id', 'users.id')
            ->groupBy('events.user_id')
    ]);

    // ⭐ violation_rate（events_count を参照するが OK）
    $query->addSelect(DB::raw("
        CASE 
            WHEN (
                SELECT COUNT(*) 
                FROM events 
                WHERE events.user_id = users.id 
                AND events.del_flg = 0
            ) = 0 
            THEN 0
            ELSE (
                (
                    SELECT COUNT(reports.id)
                    FROM events 
                    LEFT JOIN reports ON reports.event_id = events.id
                    WHERE events.user_id = users.id
                )
                /
                (
                    SELECT COUNT(*) 
                    FROM events 
                    WHERE events.user_id = users.id
                    AND events.del_flg = 0
                )
            )
        END AS violation_rate
    "));

    // 並び替え
    $users = $query
        ->orderByDesc('violation_rate')
        ->orderByDesc('updated_at')
        ->paginate(20);

    return view('admin.users.index', compact('users', 'keyword'));
}

    public function suspendConfirm($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.suspend_confirm', compact('user'));
    }

    public function suspend($id)
{
    $user = User::findOrFail($id);

    // 利用停止（del_flg を 1 にする）
    $user->del_flg = 1;
    $user->save();

    return redirect()->route('users.index')
        ->with('success', $user->name . ' さんを利用停止にしました');
}

    public function unsuspendConfirm($id)
{
    $user = User::findOrFail($id);

    return view('admin.users.unsuspend_confirm', compact('user'));
}

    public function unsuspend($id)
{
    $user = User::findOrFail($id);

    // 利用停止（del_flg を 0 にする）
    $user->del_flg = 0;
    $user->save();

    return redirect()->route('users.index')
        ->with('success', $user->name . ' さんを利用可能にしました');
}
}