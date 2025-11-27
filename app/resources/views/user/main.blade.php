@extends('layouts.app')
@section('title', 'ユーザーメイン')

@section('content')
<form action="{{ route('user.events.index') }}" method="GET" class="p-4 bg-light rounded shadow-sm">

    {{-- ================================
        上部アクション（タイトル＋ボタン）
    ================================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h2 class="m-0">イベント一覧</h2>

        <div class="d-flex gap-2 mt-2 mt-md-0">
            <a href="{{ route('user.profile', Auth::id()) }}" class="btn btn-outline-primary">
                <i class="bi bi-person-circle me-1"></i> プロフィール
            </a>

            @if(Auth::check() && Auth::user()->role == 0)
                <a href="{{ route('host.events.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-lg me-1"></i> 新規イベント作成
                </a>
            @endif
        </div>
    </div>

    {{-- ================================
        検索フォーム
    ================================= --}}
    <div class="row g-3">

        {{-- キーワード --}}
        <div class="col-md-3">
            <label class="form-label">キーワード</label>
            <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}">
        </div>

        {{-- 開催日 --}}
        <div class="col-md-4">
            <label class="form-label">開催日</label>
            <div class="d-flex align-items-center">
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                <span class="mx-2">〜</span>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
        </div>

        {{-- カテゴリ（企業寄り） --}}
        <div class="col-md-3">
            <label class="form-label">イベント種別</label>
            <select name="category" class="form-select">
                <option value="">すべて</option>
                <option value="meeting"  {{ request('category')=='meeting' ? 'selected':'' }}>オンラインミーティング</option>
                <option value="seminar"  {{ request('category')=='seminar' ? 'selected':'' }}>セミナー</option>
                <option value="workshop" {{ request('category')=='workshop'? 'selected':'' }}>ワークショップ</option>
                <option value="sports"   {{ request('category')=='sports'  ? 'selected':'' }}>スポーツイベント</option>
                <option value="party"    {{ request('category')=='party'   ? 'selected':'' }}>交流会</option>
            </select>
        </div>

        {{-- 検索ボタン --}}
        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100 me-2">検索</button>
            <a href="{{ route('user.events.index') }}" class="btn btn-secondary w-100">クリア</a>
        </div>

        {{-- 詳細検索（時間） --}}
        <details class="col-12 mt-2">
            <summary class="mb-2">詳細検索</summary>

            <div class="row g-2">
                <div class="col-md-4 d-flex align-items-center">
                    <label class="me-2">時間</label>
                    <input type="time" name="start_time" class="form-control" value="{{ request('start_time') }}">
                    <span class="mx-2">〜</span>
                    <input type="time" name="end_time" class="form-control" value="{{ request('end_time') }}">
                </div>
            </div>
        </details>
    </div>

    {{-- ================================
        タブ切り替え
    ================================= --}}
    <div class="tabs my-4">
        <button class="tab active" data-type="all">全イベント</button>
        <button class="tab" data-type="joined">参加済み</button>
        <button class="tab" data-type="bookmarked">ブックマーク</button>
        <button class="tab" data-type="hosted">主催イベント</button>
    </div>

    {{-- ================================
        イベントカード一覧
    ================================= --}}
    <div id="event-list">
        @include('layouts.event_cards', ['events' => $events])
    </div>

</form>

<script src="/js/tab.js"></script>
@endsection