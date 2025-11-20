<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Event;
use App\Models\User;

class AdminApplicationController extends Controller
{
    public function observe(Request $request)
{
    $keyword = $request->input('keyword');

    // 🔍 参加者数TOP10（人気ランキング）
    $popularEvents = Event::with('user')
        ->withCount('applications')
        ->orderByDesc('applications_count')
        ->limit(10)
        ->get();

    // 🔍 検索（イベント名 or 主催者名）
    $query = Event::with(['user', 'applications'])
        ->withCount('applications');

    if (!empty($keyword)) {
        $query->where('title', 'LIKE', "%{$keyword}%")
              ->orWhereHas('user', function ($q) use ($keyword) {
                  $q->where('name', 'LIKE', "%{$keyword}%");
              });
    }

    $events = $query->orderByDesc('updated_at')->paginate(20);

    return view('admin.applications.observe', compact(
        'events',
        'popularEvents',
        'keyword'
    ));
}
}