@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-6">✅ 登録内容確認</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            @if ($tempPath)
            <img src="{{ asset('storage/' . $tempPath) }}" class="rounded shadow w-full">
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

    <form action="{{ route('host.events.store.complete') }}" method="POST">
        @csrf

        @foreach ($data as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        {{-- ⭐ 一時画像パスを hidden で送る --}}
        <input type="hidden" name="temp_image" value="{{ $tempPath }}">

        <button>登録する</button>
    </form>
</div>
@endsection