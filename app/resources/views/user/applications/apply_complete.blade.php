@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow text-center">

    <h1 class="text-2xl font-bold mb-6">✅ 登録が完了しました！</h1>

    <div class="mb-4">
        <p class="font-semibold">イベント名</p>
        <p class="border p-2 rounded bg-gray-100 inline-block">{{ $event->title }}</p>
    </div>

    <div class="mb-4">
        <p class="font-semibold">開催日時</p>
        <p class="border p-2 rounded bg-gray-100 inline-block">
            {{ $event->date }} {{ $event->start_time }}〜{{ $event->end_time }}
        </p>
    </div>

    <button onclick="window.location='{{ route('user.events.index') }}'"
        class="px-6 py-3 bg-blue-600 text-black rounded-lg shadow hover:bg-blue-700 transition">
        メインページに戻る
    </button>

</div>
@endsection