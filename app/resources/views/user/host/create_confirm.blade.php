@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-6">✅ 登録内容確認</h1>

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


    <form action="{{ route('host.events.store.complete') }}" method="POST" class="mt-6">
        @csrf
        @foreach ($data as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        <div class="flex justify-between">
            <a href="{{ url()->previous() }}" class="text-blue-600 hover:underline">訂正する</a>
            <button class="px-4 py-2 bg-blue-600 text-balck rounded">登録する</button>
        </div>
    </form>
</div>
@endsection