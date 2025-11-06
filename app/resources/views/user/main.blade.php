@extends('layouts.app')

@section('title', 'ユーザーメイン')

@section('content')
  <form action="{{ route('search.events') }}" method="GET" class="p-4 bg-light rounded">
    <div class="d-flex gap-3 align-items-end flex-wrap">
      <div>
        <label>キーワード</label>
        <input type="text" name="keyword" class="form-control">
      </div>

      <div>
        <label>開催日</label>
        <div class="d-flex align-items-center">
          <input type="date" name="start_date" class="form-control">
          <span class="mx-2">〜</span>
          <input type="date" name="end_date" class="form-control">
        </div>
      </div>

      <div>
        <label>配信形式</label>
        <select name="platform" class="form-select">
          <option value="">すべて</option>
          <option value="twitch">Twitch</option>
          <option value="youtube">YouTube</option>
        </select>
      </div>

      <details class="w-100 mt-2">
        <summary>詳細検索</summary>
        <div class="d-flex mt-2 align-items-center">
          <label class="me-2">時間</label>
          <input type="time" name="start_time" class="form-control">
          <span class="mx-2">〜</span>
          <input type="time" name="end_time" class="form-control">
        </div>
      </details>

      <div class="ms-auto">
        <button type="submit" class="btn btn-primary">検索</button>
        <a href="{{ route('search.events') }}" class="btn btn-secondary">クリア</a>
      </div>
    </div>
    <div class="tabs">
      <button class="tab" data-type="joined">参加済み</button>
      <button class="tab" data-type="bookmarked">ブックマーク</button>
      <button class="tab" data-type="hosted">主催イベント</button>
    </div>
    <div id="event-list">
    @include('layouts.event_cards', ['events' => $events])
    </div>
  </form>
@vite(['resources/js/tab.js'])
@endsection