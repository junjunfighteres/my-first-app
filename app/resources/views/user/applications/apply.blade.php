@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-4">🗓 イベント参加申込</h1>

    <form action="{{ route('events.confirm') }}" method="POST">
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

        <div class="mb-4">
            <label class="font-semibold">コメント（任意）</label>
            <textarea name="comment" class="w-full border rounded p-2"></textarea>
        </div>

        <div class="flex justify-between mt-4">
            <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-300 rounded">戻る</a>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-black rounded">入力内容を確認する</button>
        </div>
    </form>
</div>
@endsection