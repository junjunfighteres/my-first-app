<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        return $this->renderProfile($user, false);
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

        // IDが自分なら編集モード
        $readonly = ($user->id !== Auth::id());

        return $this->renderProfile($user, $readonly);
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