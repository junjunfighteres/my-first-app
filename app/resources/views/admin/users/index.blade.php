@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">ユーザー管理</h1>

    <a href="{{ route('admin.home') }}" class="btn btn-secondary mb-3">← ダッシュボードへ戻る</a>

    {{-- 検索フォーム --}}
    <table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>名前</th>
            <th>メール</th>
            <th>イベント数</th>
            <th>違反総数</th>
            <th>違反率</th>
            <th>利用状態</th>
            <th>最終更新</th>
            <th>操作</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>

                <td>{{ $user->events_count }}</td>
                <td>{{ $user->total_reports_count ?? 0 }}</td>
                <td>{{ number_format($user->violation_rate, 2) }}</td>

                {{-- 利用状態（del_flg） --}}
                <td>{{ $user->del_flg == 0 ? '利用可' : '停止中' }}</td>

                <td>{{ $user->updated_at }}</td>

                <td>@if ($user->del_flg == 0)
                {{-- 利用停止 --}}
                    <form action="{{ route('admin.users.suspend.confirm', $user->id) }}"
                        method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">
                            利用停止
                        </button>
                    </form>
                    @else
                    {{-- 利用再開 --}}
                    <form action="{{ route('admin.users.unsuspend.confirm', $user->id) }}"
                        method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            利用再開
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

    {{ $users->links() }}

</div>
@endsection