@extends('layouts.app')

@section('title', 'ユーザーメイン')

@section('content')
  <form action="{{ route('events.index') }}" method="GET" class="p-4 bg-light rounded">
      {{-- ✅ 上部アクション行（検索クリア＋新規イベント） --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="m-0">イベント一覧</h2>

      {{-- ✅ 主催者(role=0)のみ表示 --}}
      @if(Auth::check() && Auth::user()->role == 0)
        <a href="{{ route('host.events.create') }}" 
          class="btn btn-success d-flex align-items-center"
          style="gap:6px;">
            <span style="font-size:18px;">＋</span> 新規イベント作成
        </a>
        @endif
    </div>

  <div class="d-flex gap-3 align-items-end flex-wrap">
      <div>
        <label>キーワード</label>
        <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}">
      </div>

      <div>
        <label>開催日</label>
        <div class="d-flex align-items-center">
          <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
          <span class="mx-2">〜</span>
          <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
      </div>

      <div>
        <label>配信形式</label>
        <select name="platform" class="form-select">
          <option value="">すべて</option>
          <option value="twitch" {{ request('platform') == 'twitch' ? 'selected' : '' }}>Twitch</option>
          <option value="youtube" {{ request('platform') == 'youtube' ? 'selected' : '' }}>YouTube</option>
        </select>
      </div>

      <details class="w-100 mt-2">
        <summary>詳細検索</summary>
        <div class="d-flex mt-2 align-items-center">
          <label class="me-2">時間</label>
          <input type="time" name="start_time" class="form-control" value="{{ request('keyword') }}">
          <span class="mx-2">〜</span>
          <input type="time" name="end_time" class="form-control" value="{{ request('keyword') }}">
        </div>
      </details>

      <div class="ms-auto">
        <button type="submit" class="btn btn-primary">検索</button>
        <a href="{{ route('events.index') }}" class="btn btn-secondary">クリア</a>
      </div>
    </div>
    <div class="tabs my-3">
      <button class="tab active" data-type="all">全イベント</button>
      <button class="tab" data-type="joined">参加済み</button>
      <button class="tab" data-type="bookmarked">ブックマーク</button>
      <button class="tab" data-type="hosted">主催イベント</button>
    </div>

    <div id="event-list">
    @include('layouts.event_cards', ['events' => $events])
    </div>
  </form>
<script src="/js/tab.js"></script>
@endsection