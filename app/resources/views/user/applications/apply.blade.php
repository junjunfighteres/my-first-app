@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-4">
        🗓 {{ $isJoined ? 'イベント参加キャンセル' : 'イベント参加申込' }}
    </h1>

    <form action="{{ $isJoined ? route('events.cancel', $event->id) : route('events.confirm') }}"  method="POST">
        @csrf
        <input type="hidden" name="event_id" value="{{ $event->id }}">

        <div class="mb-4">
            <label class="font-semibold">イベント名</label>
            <p class="border p-2 rounded bg-gray-100">{{ $event->title }}</p>
        </div>

        <div class="mb-4">
            <label class="font-semibold">開催日時</label>
            <p class="border p-2 rounded bg-gray-100">
                {{ $event->date }} {{ $event->start_time }}〜{{ $event->end_time }}
            </p>
        </div>

        <div class="mb-4">
            <label class="font-semibold">ユーザー名</label>
            <p class="border p-2 rounded bg-gray-100">{{ $user->name }}</p>
        </div>

        <div class="mb-4">
            <label class="font-semibold">メールアドレス</label>
            <p class="border p-2 rounded bg-gray-100">{{ $user->email }}</p>
        </div>

        {{-- コメント欄は参加時のみ表示 --}}
        @unless($isJoined)
        <div class="mb-4">
            <label class="font-semibold">コメント（任意）</label>
            <textarea name="comment" class="w-full border rounded p-2"></textarea>
        </div>
        @endunless

        <div class="flex justify-between mt-4">
            <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-300 rounded">戻る</a>
            @if ($isJoined)
                {{-- 参加済みならキャンセルボタン --}}
                <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-black rounded">
                    参加をキャンセルする
                </button>
            @else
                {{-- 未参加なら申込ボタン --}}
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-black rounded">
                    入力内容を確認する
                </button>
            @endif
        </div>
    </form>
</div>
@endsection