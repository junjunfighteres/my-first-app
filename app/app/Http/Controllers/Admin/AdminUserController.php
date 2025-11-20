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

        $users = User::where('role', '!=', 2) // 管理者自身以外
            ->when($keyword, function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%$keyword%")
                  ->orWhere('email', 'LIKE', "%$keyword%");
            })
            ->where('del_flg', 0)
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users', 'keyword'));
    }

    /**
     * 利用停止確認画面
     */
    public function suspendConfirm(User $user)
    {
        return view('admin.users.suspend_confirm', compact('user'));
    }

    /**
     * 利用停止処理
     */
    public function suspendComplete(Request $request, User $user)
    {
        // del_flg = 1 に変更
        $user->update([
            'del_flg' => 1
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'ユーザーを利用停止にしました。');
    }
}