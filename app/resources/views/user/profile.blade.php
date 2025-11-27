@extends('layouts.app')

@section('content')

<style>
    .avatar-wrapper {
        position: relative;
        width: 160px;
        height: 160px;
        margin: 0 auto;
    }
    .avatar-img {
        width: 160px;
        height: 160px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ddd;
    }
    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background: rgba(0,0,0,0.4);
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: 0.3s;
        cursor: pointer;
        color: #fff;
        font-size: 14px;
    }
    .avatar-wrapper:hover .avatar-overlay {
        opacity: 1;
    }
</style>

<div class="container mt-5">

    {{-- 自分のプロフィール（編集モード） --}}
    @if(empty($readonly))

        <h2 class="text-center mb-4">プロフィール設定</h2>

        {{-- アイコン編集 --}}
        <div class="avatar-wrapper">
            <img 
                id="preview"
                src="{{ $user->avatar_path ? asset('storage/'.$user->avatar_path) : asset('/img/default-user.png') }}" 
                class="avatar-img"
            >

            <div class="avatar-overlay" onclick="document.getElementById('avatarInput').click();">
                画像を変更
            </div>
        </div>

        {{-- 退会ボタン --}}
        <form action="{{ route('user.withdraw') }}" method="POST" class="text-center mt-4">
            @csrf
            <button class="btn btn-danger">退会する</button>
        </form>

        {{-- ⭐ ここからプロフィール更新フォーム --}}
        <form action="{{ route('user.profile.avatar') }}" 
            method="POST" 
            enctype="multipart/form-data" 
            class="text-center mt-4">

            @csrf
            {{-- 画像 --}}
            <div class="mb-3">
                <label class="form-label">プロフィール画像</label>
                <input type="file" name="avatar" class="form-control">
            </div>

            {{-- 自己紹介 --}}
            <div class="form-group mt-4" style="max-width: 600px; margin: 0 auto;">
                <label>自己紹介</label>
                <textarea name="self_introduction" class="form-control" rows="4">{{ old('self_introduction', $user->self_introduction) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-3">保存する</button>

        </form>

        @if(session('success'))
            <p class="text-success text-center mt-3">{{ session('success') }}</p>
        @endif

    {{-- ▼▼ 閲覧専用モード（他人のプロフ閲覧） ▼▼ --}}
    @else

        <h2 class="text-center mb-4">{{ $user->name }} さんのプロフィール</h2>

        <div class="avatar-wrapper mb-4">
            <img 
                src="{{ $user->avatar_path ? asset('storage/'.$user->avatar_path) : asset('/img/default-user.png') }}" 
                class="avatar-img">
        </div>

        {{-- 自己紹介 --}}
        @if($user->self_introduction)
            <p class="text-center mt-3" style="white-space: pre-wrap;">
                {{ $user->self_introduction }}
            </p>
        @else
            <p class="text-center text-muted mt-3">自己紹介はまだありません。</p>
        @endif

    @endif

    {{-- ▼▼ サイト統計情報（全ユーザー向け） ▼▼ --}}
    <div class="p-4 bg-gray-100 rounded-lg shadow mt-5">
        <h2 class="text-xl font-bold mb-3">📊 サイト統計情報</h2>

        <ul class="space-y-2 text-lg">
            <li>・登録ユーザー数：<span class="font-bold">{{ $userCount }}</span> 名</li>
            <li>・イベント総数：<span class="font-bold">{{ $eventCount }}</span> 件</li>
            <li>・違反報告件数：<span class="font-bold">{{ $reportCount }}</span> 件</li>
            <li>・参加申込総数：<span class="font-bold">{{ $joinCount }}</span> 件</li>
        </ul>
    </div>

    {{-- 主催イベント --}}
    <h3 class="mt-5">主催イベント</h3>
    @if($hosted->count())
        <div class="event-cards">
            @foreach ($hosted as $event)
                <div class="event-card">
                    <h4>{{ $event->title }}</h4>
                    <p>📅 {{ $event->date }}</p>
                    <a href="{{ route('user.events.show', $event->id) }}">詳細を見る</a>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted">主催イベントはありません</p>
    @endif

    {{-- 参加イベント --}}
    <h3 class="mt-5">参加イベント</h3>
    @if($joined->count())
        <div class="event-cards">
            @foreach ($joined as $event)
                <div class="event-card">
                    <h4>{{ $event->title }}</h4>
                    <p>📅 {{ $event->date }}</p>
                    <a href="{{ route('user.events.show', $event->id) }}">詳細を見る</a>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted">参加イベントはありません</p>
    @endif

    {{-- カレンダー --}}
    <h3 class="mt-5">スケジュール</h3>
    <div id='calendar'></div>

</div>{{-- container end --}}

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('preview').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

{{-- FullCalendar --}}
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    var events = [
        @foreach($hosted as $e)
        {
            title: "{{ $e->title }}",
            start: "{{ $e->date }}",
            color: "blue",
        },
        @endforeach

        @foreach($joined as $e)
        {
            title: "{{ $e->title }}",
            start: "{{ $e->date }}",
            color: "green",
        },
        @endforeach
    ];

    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        events: events
    });

    calendar.render();
});
</script>

@endsection