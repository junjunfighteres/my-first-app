@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow">
    {{-- ヘッダー --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">イベント非表示の確認</h1>
    </div>

    {{-- 戻るリンク --}}
    <div class="mb-4">
        <a href="{{ route('events.show', $event->id) }}" class="text-blue-600 hover:underline">
            ← イベント詳細に戻る
        </a>
    </div>

    <div class="border rounded-lg p-4 bg-gray-50">
        <p class="mb-4">
            このイベントを<strong class="text-red-600">非表示</strong>にしますか？<br>
            非表示にすると、一般ユーザーからは閲覧できなくなります。
        </p>

        <div class="mb-2">
            <span class="font-semibold">イベントID：</span>{{ $event->id }}
        </div>
        <div class="mb-2">
            <span class="font-semibold">タイトル：</span>{{ $event->title }}
        </div>
        <div class="mb-2">
            <span class="font-semibold">開催日：</span>{{ $event->date }} {{ $event->start_time }}〜{{ $event->end_time }}
        </div>
        <div class="mb-2">
            <span class="font-semibold">主催者ユーザーID：</span>{{ $event->user_id }}
        </div>

        <form action="{{ route('admin.events.hidden.complete', $event->id) }}" method="POST" class="mt-6 flex justify-between">
            @csrf
            <a href="{{ route('events.show', $event->id) }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                キャンセル
            </a>
            <button type="submit" class="px-4 py-2 bg-red-600 text-black rounded hover:bg-red-700">
                このイベントを非表示にする
            </button>
        </form>
    </div>
</div>
@endsection