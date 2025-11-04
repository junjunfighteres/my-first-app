@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-2xl shadow-lg">

    {{-- 戻るリンク --}}
    <div class="mb-4">
        <!-- <a href="{{ route('search.events') }}" class="text-blue-600 hover:underline">🔙 イベント一覧へ戻る</a> -->
    </div>

    {{-- イベント概要 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-b pb-6 mb-6">
        <div>
            <!-- <img src="{{ $event->image_url ?? '/images/default_event.png' }}"  -->
                 alt="イベント画像" 
                 class="w-full rounded-lg shadow">
        </div>
        <div>
            <h1 class="text-2xl font-bold mb-2">{{ $event->title }}</h1>
            <p class="text-gray-700 mb-2">
                主催者：
                <a href="{{ route('user_main', ['id' => $event->organizer_id]) }}" 
                   class="text-blue-600 hover:underline">
                   {{ $event->organizer_name }}
                </a>
            </p>
            <p>開催日：{{ $event->start_date }} {{ $event->start_time }}〜{{ $event->end_time }}</p>
            <p>配信形式：{{ $event->format }}</p>
            <p>定員：{{ $event->capacity }}名</p>
            <p>現在参加数：{{ $event->participants_count }}人</p>

            {{-- アクションボタン --}}
            <div class="mt-4 flex flex-wrap gap-3">
                <form action="{{ route('user_main', $event->id) }}" method="POST">
                    @csrf
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">参加する</button>
                </form>

                <button class="bg-yellow-400 hover:bg-yellow-500 px-4 py-2 rounded-lg">ブックマーク</button>
                <button class="bg-red-400 hover:bg-red-500 px-4 py-2 rounded-lg">違反報告</button>

                @if (Auth::check() && Auth::user()->role == 2)
                    <button class="bg-gray-400 hover:bg-gray-500 px-4 py-2 rounded-lg">非表示にする</button>
                @endif
            </div>
        </div>
    </div>

    {{-- 紹介文（折りたたみ） --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">📝 紹介文</h2>
        <div x-data="{ open: false }">
            <p x-show="open" class="text-gray-800">{{ $event->description }}</p>
            <p x-show="!open" class="text-gray-800 line-clamp-3">{{ Str::limit($event->description, 100) }}</p>
            <button @click="open = !open" class="text-blue-600 hover:underline mt-2">
                <span x-show="!open">続きを読む</span>
                <span x-show="open">閉じる</span>
            </button>
        </div>
    </div>

    {{-- コメント欄 --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">💬 コメント欄</h2>
        <form action="{{ route('user_main', $event->id) }}" method="POST" class="mb-4">
            @csrf
            <div class="flex gap-2">
                <input type="text" name="comment" placeholder="コメントを入力..." 
                       class="flex-1 border rounded-lg p-2">
                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg">送信</button>
            </div>
        </form>
    </div>

    {{-- 関連イベント --}}
    <div>
        <h2 class="text-xl font-semibold mb-3">👥 関連イベント</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach ($related as $rel)
            <div class="card border rounded-lg p-3 shadow-sm">
                {{-- 画像がある場合だけ表示 --}}
                @if (isset($rel->image_url))
                    <img src="{{ asset($rel->image_url) }}" alt="{{ $rel->title }}" class="w-full h-32 object-cover rounded">
                @else
                    {{-- 画像がない場合はダミー枠を出す --}}
                    <div class="w-full h-32 bg-gray-200 flex items-center justify-center text-gray-500">
                        画像なし
                    </div>
                @endif

                <h3 class="font-semibold mt-2">{{ $rel->title }}</h3>
            </div>
        @endforeach
        </div>
    </div>

</div>
@endsection