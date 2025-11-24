<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Event;
use App\Models\Report;
use App\Models\Application;
use App\Models\Bookmark;

class ProfileController extends Controller
{
    public function show()
{
    $user = Auth::user();

    // ▼ 統計データ
    $userCount = User::where('del_flg', 0)->count();
    $eventCount = Event::where('del_flg', 0)->count();
    $reportCount = Report::count();
    $joinCount = Application::count();

    // ▼ 主催イベント
    $hosted = Event::where('user_id', $user->id)->get();

    // ▼ 参加イベント
    $joined = Event::whereHas('applications', function($q) use ($user) {
        $q->where('user_id', $user->id);
    })->get();

    return view('user.profile', [
        'user' => $user,
        'hosted' => $hosted,
        'joined' => $joined,
        'readonly' => false,
        'userCount' => $userCount,
        'eventCount' => $eventCount,
        'reportCount' => $reportCount,
        'joinCount' => $joinCount,
    ]);
}

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'nullable|image|max:10240', // 10MBまで
        ]);

        $user = Auth::user();

        // 画像があれば保存
        if ($request->hasFile('avatar')) {

            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $path;
        }

        // 自己紹介更新
        $user->self_introduction = $request->self_introduction;

        $user->save();

        return back()->with('success', 'プロフィールを更新しました！');
    }

    public function showOtherUser($id)
{
    $user = User::findOrFail($id);

    // ▼ 統計データ
    $userCount = User::where('del_flg', 0)->count();
    $eventCount = Event::where('del_flg', 0)->count();
    $reportCount = Report::count();
    $joinCount = Application::count();

    // ▼ 主催/参加イベント取得
    $hosted = Event::where('user_id', $user->id)->get();
    $joined = Event::whereHas('applications', function($q) use ($user) {
        $q->where('user_id', $user->id);
    })->get();

    $readonly = ($user->id !== Auth::id());

    return view('user.profile', [
        'user' => $user,
        'hosted' => $hosted,
        'joined' => $joined,
        'readonly' => $readonly,
        'userCount' => $userCount,
        'eventCount' => $eventCount,
        'reportCount' => $reportCount,
        'joinCount' => $joinCount,
    ]);
}

    public function withdraw(Request $request)
    {
        $user = Auth::user();

        $user->del_flg = 1;
        $user->save();

        Auth::logout();

        return redirect()->route('withdraw.complete');
    }

    private function renderProfile($user, $readonly)
    {
        $hosted = $user->events()->get();        // 主催イベント
        $joined = $user->joinedEvents()->get();  // 参加イベント

        return view('user.profile', [
            'user'     => $user,
            'readonly' => $readonly,
            'hosted'   => $hosted,
            'joined'   => $joined,
        ]);
    }
}