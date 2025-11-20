@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">

    <h1 class="mb-4">参加申込監視</h1>

    <a href="{{ route('admin.home') }}" class="btn btn-secondary mb-3">← ダッシュボードへ戻る</a>

    <h3 class="mt-4">🔍 検索結果</h3>

    <form method="GET" action="{{ route('admin.applications.observe') }}" class="mb-4 d-flex gap-2">
        <input 
            type="text"
            name="keyword"
            value="{{ request('keyword') }}"
            class="form-control w-auto"
            placeholder="イベント名・主催者名">

        <button class="btn btn-primary">検索</button>
        <a href="{{ route('admin.applications.observe') }}" class="btn btn-secondary">条件クリア</a>
    </form>

<table class="table table-bordered mt-2">
    <thead>
        <tr>
            <th>イベント名</th>
            <th>主催者</th>
            <th>参加人数</th>
            <th>最終更新</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($events as $event)
            <tr>
                <td>{{ $event->title }}</td>
                <td>{{ $event->user->name }}</td>
                <td>{{ $event->applications_count }} 名</td>
                <td>{{ $event->updated_at }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">検索結果がありません</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $events->links() }}

</div>
@endsection