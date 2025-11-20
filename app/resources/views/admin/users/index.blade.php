@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">ユーザー管理</h1>

    <a href="{{ route('admin.home') }}" class="btn btn-secondary mb-3">← ダッシュボードへ戻る</a>

    {{-- 検索フォーム --}}
    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-3 d-flex gap-2">
        <input type="text" name="keyword" value="{{ $keyword }}" class="form-control w-auto"
               placeholder="名前・メールアドレス（部分一致）">
        <button class="btn btn-primary">検索</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">条件クリア</a>
    </form>

    {{-- ユーザー一覧 --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>名前</th>
                <th>メール</th>
                <th>状態</th>
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
                    <td>{{ $user->del_flg == 0 ? '利用可' : '停止中' }}</td>
                    <td>{{ $user->updated_at }}</td>
                    <td>
                        {{-- 利用停止ボタン --}}
                        <form action="{{ route('admin.users.suspend.confirm', $user->id) }}"
                              method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">
                                利用停止
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}

</div>
@endsection