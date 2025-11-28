<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Event;
use App\Models\Application;
use App\Models\Report;
use App\Models\Bookmark;

class ProfileController extends Controller
{
    /**
     * 自分のプロフィール表示
     */
    public function show()
    {
        $user = Auth::user();

        // ▼ 主催イベント
        $hosted = Event::where('user_id', $user->id)
            ->where('del_flg', 0)
            ->get();

        // ▼ 参加イベント
        $joined = Event::whereHas('applications', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->get();

        // ▼ 個人の統計値
        $hostCount   = $hosted->count();
        $joinedCount = $joined->count();

        return view('user.profile', [
            'user'         => $user,
            'readonly'     => false,
            'hosted'       => $hosted,
            'joined'       => $joined,
            'hostCount'    => $hostCount,
            'joinedCount'  => $joinedCount,
        ]);
    }

    /**
     * アバターアップデート + 自己紹介更新
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'nullable|image|max:10240', // 10MB
        ]);

        $user = Auth::user();

        // 画像があれば保存
        if ($request->hasFile('avatar')) {

            // 旧画像削除
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            // 新しい画像を保存
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $path;
        }

        // 自己紹介更新
        $user->self_introduction = $request->self_introduction;
        $user->save();

        return back()->with('success', 'プロフィールを更新しました！');
    }

    /**
     * 他ユーザーのプロフィール表示
     */
    public function showOtherUser($id)
    {
        $user = User::findOrFail($id);

        // ▼ 主催イベント
        $hosted = Event::where('user_id', $user->id)
            ->where('del_flg', 0)
            ->get();

        // ▼ 参加イベント
        $joined = Event::whereHas('applications', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->get();

        // ▼ 個人の統計値
        $hostCount   = $hosted->count();
        $joinedCount = $joined->count();

        $readonly = ($user->id !== Auth::id());

        return view('user.profile', [
            'user'         => $user,
            'readonly'     => $readonly,
            'hosted'       => $hosted,
            'joined'       => $joined,
            'hostCount'    => $hostCount,
            'joinedCount'  => $joinedCount,
        ]);
    }

    /**
     * 退会処理（フラグ変更）
     */
    public function withdraw(Request $request)
    {
        $user = Auth::user();
        $user->del_flg = 1;
        $user->save();

        Auth::logout();

        return redirect()->route('withdraw.complete');
    }
}