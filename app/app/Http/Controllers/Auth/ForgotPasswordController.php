<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => '登録されていないメールアドレスです']);
        }

        // ランダムトークン生成
        $token = Str::random(60);

        // ユーザーに保存
        $user->password_reset_token = $token;
        $user->save();

        // メール送信
        Mail::to($user->email)->send(new ResetPasswordMail($token));

        return back()->with('status', 'パスワードリセットメールを送信しました');
    }

    public function showResetForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
            'token' => 'required'
        ]);

        $user = User::where('email', $request->email)
                    ->where('password_reset_token', $request->token)
                    ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'トークンが無効です']);
        }

        $user->password = Hash::make($request->password);
        $user->password_reset_token = null;
        $user->save();

        return redirect('/login')->with('status', 'パスワードが更新されました！');
    }
}
