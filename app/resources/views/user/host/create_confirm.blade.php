@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-6">✅ 登録内容確認</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            @if ($tempPath)
                <img src="{{ asset('storage/'.$tempPath) }}" class="rounded shadow w-full">
            @else
                <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-500">
                    画像なし
                </div>
            @endif
        </div>

        <div>
            <p><strong>タイトル：</strong>{{ $data['title'] }}</p>
            <p><strong>開催日：</strong>{{ $data['date'] }}</p>
            <p><strong>時間：</strong>{{ $data['start_time'] }}〜{{ $data['end_time'] }}</p>
            <p><strong>イベント種別：</strong>{{ $data['format'] }}</p>
            <p><strong>定員：</strong>{{ $data['capacity'] }} 名</p>
            <p><strong>公開設定：</strong>{{ $data['status'] }}</p>
            <p><strong>説明：</strong>{{ $data['description'] }}</p>
        </div>

    </div>

    <form action="{{ route('host.events.store.complete') }}" method="POST">
        @csrf

        {{-- 必須データ --}}
        <input type="hidden" name="title"        value="{{ e($data['title']) }}">
        <input type="hidden" name="date"         value="{{ e($data['date']) }}">
        <input type="hidden" name="start_time"   value="{{ e($data['start_time']) }}">
        <input type="hidden" name="end_time"     value="{{ e($data['end_time']) }}">
        <input type="hidden" name="format"       value="{{ e($data['format']) }}">
        <input type="hidden" name="capacity"     value="{{ e($data['capacity']) }}">
        <input type="hidden" name="status"       value="{{ e($data['status']) }}">
        <input type="hidden" name="description"  value="{{ e($data['description']) }}">

        {{-- 一時画像 --}}
        <input type="hidden" name="temp_image" value="{{ $tempPath }}">

        <div class="mt-6 flex justify-end">
            <button class="px-4 py-2 bg-blue-600 text-black rounded shadow hover:bg-blue-700">
                登録する
            </button>
        </div>
    </form>

</div>
@endsection