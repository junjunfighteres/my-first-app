@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-6">✏️ 編集内容確認</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <div>
            @if (!empty($data['image_path']))
                <img src="{{ asset('storage/' . $data['image_path']) }}" 
                     class="rounded shadow w-full">
            @else
                <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                    画像なし
                </div>
            @endif
        </div>

        <div>
            <p><strong>タイトル：</strong>{{ $data['title'] }}</p>
            <p><strong>開催日：</strong>{{ $data['date'] }}</p>
            <p><strong>時間：</strong>{{ $data['start_time'] }}〜{{ $data['end_time'] }}</p>
            <p><strong>形式：</strong>{{ $data['format'] }}</p>
            <p><strong>定員：</strong>{{ $data['capacity'] }}名</p>
            <p><strong>公開設定：</strong>{{ $data['status'] }}</p>
            <p><strong>説明：</strong>{{ $data['description'] }}</p>
        </div>

    </div>

    <form action="{{ route('host.events.update.complete') }}" method="POST" class="mt-6">
        @csrf

        {{-- 基本情報 --}}
        <input type="hidden" name="event_id" value="{{ $data['event_id'] }}">
        <input type="hidden" name="title" value="{{ $data['title'] }}">
        <input type="hidden" name="date" value="{{ $data['date'] }}">
        <input type="hidden" name="start_time" value="{{ $data['start_time'] }}">
        <input type="hidden" name="end_time" value="{{ $data['end_time'] }}">
        <input type="hidden" name="format" value="{{ $data['format'] }}">
        <input type="hidden" name="capacity" value="{{ $data['capacity'] }}">
        <input type="hidden" name="status" value="{{ $data['status'] }}">

        {{-- ⭐これが必要！！ --}}
        <input type="hidden" name="image_path" value="{{ $data['image_path'] }}">

        {{-- 説明 --}}
        <textarea name="description" hidden>{{ $data['description'] }}</textarea>

        <div class="flex justify-between">
            <a href="{{ url()->previous() }}" class="text-blue-600 hover:underline">訂正する</a>
            <button class="px-4 py-2 bg-blue-600 text-black rounded">更新する</button>
        </div>
    </form>

</div>
@endsection
