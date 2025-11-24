@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow">

    {{-- ヘッダー --}}
    <div class="flex justify-between items-center mb-6 border-b pb-3">
        <h1 class="text-3xl font-bold">管理者ダッシュボード</h1>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="px-4 py-2 bg-red-500 text-black rounded-lg">
                🔓 ログアウト
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- 管理メニュー --}}
        <div class="p-4 bg-gray-100 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-3">🔗 管理メニュー</h2>

            <div class="space-y-3">
                <a href="{{ route('events.index') }}" 
                   class="block bg-blue-500 text-black px-4 py-2 rounded-lg hover:bg-blue-600">
                    📅 イベント管理
                </a>

                <a href="{{ route('users.index') }}" 
                   class="block bg-green-500 text-black px-4 py-2 rounded-lg hover:bg-green-600">
                    👤 ユーザー管理
                </a>

                <a href="{{ route('admin.applications.observe') }}" 
                   class="block bg-purple-500 text-black px-4 py-2 rounded-lg hover:bg-purple-600">
                    📝 参加申込閲覧
                </a>
            </div>
        </div>

        {{-- 簡易データ --}}
        <div class="p-4 bg-gray-100 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-3">📊 サイト統計情報</h2>

            <ul class="space-y-2 text-lg">
                <li>・登録ユーザー数：<span class="font-bold">{{ $userCount }}</span> 名</li>
                <li>・イベント総数：<span class="font-bold">{{ $eventCount }}</span> 件</li>
                <li>・違反報告件数：<span class="font-bold">{{ $reportCount }}</span> 件</li>
                <li>・参加申込総数：<span class="font-bold">{{ $joinCount }}</span> 件</li>
            </ul>
        </div>

    </div>

</div>
@endsection