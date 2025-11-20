@extends('admin.layouts.app')

@section('content')
<div class="container mt-5">

    <h2 class="mb-4">⚠️ 利用停止確認</h2>

    <p>ユーザー名：<strong>{{ $user->name }}</strong></p>
    <p>メールアドレス：<strong>{{ $user->email }}</strong></p>

    <div class="alert alert-warning mt-3">
        このユーザーを <strong>利用停止</strong> にしますか？
    </div>

    <form method="POST" action="{{ route('admin.users.suspend.complete', $user->id) }}">
        @csrf

        <button class="btn btn-danger">はい、利用停止にする</button>

        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ml-3">
            一覧に戻る
        </a>
    </form>

</div>
@endsection