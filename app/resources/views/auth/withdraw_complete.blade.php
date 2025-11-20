@extends('layouts.app')

@section('content')
<div class="container mt-5 text-center">
    <h2>退会が完了しました</h2>
    <p class="mt-3">ご利用ありがとうございました。</p>
    <a href="{{ route('login') }}" class="btn btn-primary mt-4">ログイン画面へ戻る</a>
</div>
@endsection