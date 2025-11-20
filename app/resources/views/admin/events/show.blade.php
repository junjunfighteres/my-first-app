@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow">

    {{-- タイトル / 戻るリンク / ログアウト --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">📝 イベント詳細（管理者）</h1>

        <div class="flex gap-4">
            <a href="{{ route('admin.events.index') }}" class="text-blue-600 hover:underline">
                ← イベント一覧に戻る
            </a>
        </div>
    </div>

    {{-- 基本情報 --}}
    <div class="grid grid-cols-2 gap-6 mb-8">

        <div>
            <p class="font-semibold">イベント名</p>
            <p class="border p-3 rounded bg-gray-100">{{ $event->title }}</p>
        </div>

        <div>
            <p class="font-semibold">主催者</p>
            <p class="border p-3 rounded bg-gray-100">
                {{ $event->user->name }}
                （ID: {{ $event->user->id }}）
            </p>
        </div>

        <div>
            <p class="font-semibold">開催日時</p>
            <p class="border p-3 rounded bg-gray-100">
                {{ $event->date }} {{ $event->start_time }}〜{{ $event->end_time }}
            </p>
        </div>

        <div>
            <p class="font-semibold">公開状態</p>
            <p class="border p-3 rounded bg-gray-100">
                {{ $event->status === 'public' ? '公開' : '非公開' }}
            </p>
        </div>

        <div>
            <p class="font-semibold">違反報告数</p>
            <p class="border p-3 rounded bg-gray-100">
                {{ $event->reports->count() }} 件
            </p>
        </div>

        <div>
            <p class="font-semibold">参加者数</p>
            <p class="border p-3 rounded bg-gray-100">
                {{ $event->applications->count() }} 名
            </p>
        </div>

    </div>

    {{-- 説明文 --}}
    <div class="mb-8">
        <p class="font-semibold">説明</p>
        <div class="border p-4 rounded bg-gray-50 whitespace-pre-line">
            {{ $event->description }}
        </div>
    </div>

    {{-- アクションボタン（管理者専用） --}}
    <div class="flex gap-4">

        {{-- 違反報告をクリア（全件削除） --}}
        <form action="{{ route('admin.events.hidden.confirm', $event->id) }}" method="POST">
            @csrf
            <button class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-black rounded">
                ⚠️ 違反報告をクリア
            </button>
        </form>

        {{-- イベントを非公開へ移動 --}}
        <form action="{{ route('admin.events.hidden.confirm', $event->id) }}" method="POST">
            @csrf
            <button class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-black rounded">
                👁‍🗨 非公開にする
            </button>
        </form>

        {{-- 主催者を利用停止 --}}
        <form action="{{ route('admin.users.suspend.confirm', $event->user->id) }}" method="POST">
            @csrf
            <button class="px-4 py-2 bg-red-500 hover:bg-red-600 text-black rounded">
                ⛔ 主催者を利用停止
            </button>
        </form>

    </div>
</div>
@endsection