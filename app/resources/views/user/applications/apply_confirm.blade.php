@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-4">入力内容の確認</h1>

    <form action="{{ route('events.complete', $event->id) }}" method="POST">
        @csrf
        <input type="hidden" name="event_id" value="{{ $event->id }}">
        <input type="hidden" name="comment" value="{{ $comment }}">

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
            <label class="font-semibold">コメント（任意）</label>
            <p class="border p-2 rounded bg-gray-100">{{ $comment }}</p>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('events.apply', $event->id) }}" class="px-4 py-2 bg-gray-300 rounded">
                修正する
            </a>

            <button type="submit" class="px-4 py-2 bg-blue-500 text-black rounded">
                登録する
            </button>
        </div>

    </form>

</div>
@endsection