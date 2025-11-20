<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class DisplayController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user();
    $type = $request->query('type', 'all');
    $keyword = $request->input('keyword');
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $platform = $request->input('platform');

    // 🌟 ベースクエリ（ここで非公開除外を必ず適用！）
    $query = Event::query()
        ->where('del_flg', 0)
        ->with('user')
        ->where(function ($q) use ($user) {
            $q->where('status', 'public')
              ->orWhere('user_id', $user->id);   // ← 自分の非公開イベントは表示OK
        });

    // ==================
    // 種類別フィルタ
    // ==================
    if ($type === 'joined') {
        $query->whereIn('id', function ($sub) use ($user) {
            $sub->select('event_id')
                ->from('applications')
                ->where('user_id', $user->id);
        });
    } elseif ($type === 'bookmarked') {
        $query->whereIn('id', function ($sub) use ($user) {
            $sub->select('event_id')
                ->from('bookmarks')
                ->where('user_id', $user->id);
        });
    } elseif ($type === 'hosted') {
        $query->where('user_id', $user->id);
    }

    // ========= キーワード検索 =========
    if (!empty($keyword)) {
        $query->where(function ($q) use ($keyword) {
            $q->where('title', 'LIKE', "%{$keyword}%")
              ->orWhere('description', 'LIKE', "%{$keyword}%")
              ->orWhereHas('user', function ($uq) use ($keyword) {
                  $uq->where('name', 'LIKE', "%{$keyword}%");
              });
        });
    }

    // ========= 日付フィルタ =========
    if ($startDate) {
        $query->where('date', '>=', $startDate);
    }
    if ($endDate) {
        $query->where('date', '<=', $endDate);
    }

    // ========= 形式フィルタ =========
    if ($platform) {
        $query->where('format', $platform);
    }

    $events = $query->orderBy('date', 'asc')->paginate(10);

    return view('user.main', compact(
        'events',
        'keyword',
        'startDate',
        'endDate',
        'platform'
    ));
}

}