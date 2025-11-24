@extends('admin.layouts.app')

@section('content')
<div class="container mt-5">

    <h2 class="mb-4">🔓 利用再開 確認</h2>

    <p>ユーザー名：<strong>{{ $user->name }}</strong></p>
    <p>メールアドレス：<strong>{{ $user->email }}</strong></p>

    <div class="alert alert-success mt-3">
        このユーザーを <strong>利用再開</strong> しますか？
    </div>

    <form method="POST" action="{{ route('admin.users.unsuspend', $user->id) }}">
        @csrf

        <button class="btn btn-success">はい、再開する</button>

        <a href="{{ route('users.index') }}" class="btn btn-secondary ml-3">
            一覧に戻る
        </a>
    </form>

</div>
@endsection