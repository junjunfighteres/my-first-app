@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">イベント管理</h1>

    <a href="{{ route('admin.home') }}" class="btn btn-secondary mb-3">← ダッシュボードへ戻る</a>

    <form method="GET" action="{{ route('admin.events.index') }}" class="mb-3 d-flex gap-2">
        <input type="text" name="keyword" value="{{ $keyword ?? '' }}" class="form-control w-auto"
               placeholder="イベント名・主催者（部分一致）">
        <button class="btn btn-primary">検索</button>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">条件クリア</a>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>イベント名</th>
                <th>主催者</th>
                <th>違反数</th>
                <th>参加人数</th>
                <th>最終更新</th>
                <th>操作</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($events as $event)
                <tr>
                    <td>{{ $event->id }}</td>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->user->name }}</td>
                    <td>{{ $event->reports_count }}</td>
                    <td>{{ $event->applications_count }}</td>
                    <td>{{ $event->updated_at }}</td>
                    <td>
                        <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-info btn-sm">
                            詳細
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection