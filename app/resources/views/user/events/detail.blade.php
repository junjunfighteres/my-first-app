@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-2xl shadow-lg">

    {{-- 戻るリンク --}}
    <div class="mb-4">
        {{-- <a href="{{ route('user.events.index') }}" class="text-blue-600 hover:underline">🔙 イベント一覧へ戻る</a> --}}
    </div>

    {{-- イベント概要 --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-b pb-6 mb-6">
        <div>
            @if (!empty($event->image_path))
                <img src="{{ asset('storage/' . $event->image_path) }}"
                    alt="イベント画像"
                    class="w-full rounded-lg shadow">
            @else
                <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-500">
                    画像なし
                </div>
            @endif
        </div>
        <div>
            <h1 class="text-2xl font-bold mb-2">{{ $event->title }}</h1>
            {{-- 🔒 主催者だけ見える非公開ラベル --}}
            @if ($event->status === 'private' && Auth::id() === $event->user_id)
            <span class="inline-block bg-red-500 text-black px-3 py-1 rounded-full text-sm mb-3">
            🔒 非公開イベント（自分だけ見えます）
            </span>
            @endif
            <p class="text-gray-700 mb-2">
                主催者：
                <a href="{{ route('user.profile.other', ['id' => $event->user_id]) }}" 
                    class="text-blue-600 hover:underline">
                    {{ $event->user->name }}
                </a>
            </p>
            <p>開催日：{{ $event->start_date }} {{ $event->start_time }}〜{{ $event->end_time }}</p>
            <p>配信形式：{{ $event->format }}</p>
            <p>定員：{{ $event->capacity }}名</p>
            <p>現在参加数：{{ $event->applications_count ?? 0 }}人</p>

            {{-- アクションボタン --}}
            <div class="mt-4 flex flex-wrap gap-3">

            {{-- 一般ユーザー用（role = 0） --}}
            @if (Auth::check() && Auth::user()->role == 0 && Auth::id() !== $event->user_id)
                <a href="{{ route('events.apply', $event->id) }}" class="inline-block">
                    <button id="apply-btn-{{ $event->id }}" data-event-id="{{ $event->id }}" class="bg-blue-500 hover:bg-blue-600 text-black px-4 py-2 rounded-lg">
                        {{ $isJoined ? '参加をキャンセル' : '参加する' }}
                    </button>
                </a>

                {{-- ブックマークボタン --}}
                <button 
                    id="bookmark-btn-{{ $event->id }}"
                    data-event-id="{{ $event->id }}"
                    class="px-4 py-2 rounded-lg text-black
                        {{ $isBookmarked ? 'bg-yellow-500' : 'bg-yellow-400' }}">
                        {{ $isBookmarked ? '★ ブックマーク中' : '☆ ブックマーク' }}
                </button>

                {{-- 違反報告（一般ユーザー専用 & reports_enabled が true のときだけ） --}}
                @if (Auth::check() && Auth::user()->role === 0 && $event->reports_enabled)
                    <a href="{{ route('report.create', $event->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded-lg">
                        違反報告
                    </a>
                @endif

            {{-- 主催者用（role = 0） --}}
            @if (Auth::check() && Auth::user()->role == 0 && Auth::id() === $event->user_id)
                <div class="flex flex-row gap-4 items-center justify-start">

                    {{-- 編集ボタン --}}
                    <a href="{{ route('host.events.edit', $event->id) }}" class="inline-block">
                        <button type="button" class="bg-green-500 hover:bg-green-600 text-black px-4 py-2 rounded-lg">
                            編集する
                        </button>
                    </a>

                    {{-- 削除ボタン --}}
                    <form action="{{ route('host.events.destroy', $event->id) }}" method="POST"
                        onsubmit="return confirm('本当にこのイベントを削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-black px-4 py-2 rounded-lg">
                            削除する
                        </button>
                    </form>

                </div>
            @endif

            {{-- 管理者用（role = 2） --}}
         
            @if (Auth::check() && Auth::user()->role == 2)
                <button type="button" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">
                    非表示にする
                </button>
            @endif
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

    {{-- コメント一覧 --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">💬 参加者コメント一覧</h2>

        @if ($comments->count() > 0)
            <div class="space-y-3">
                @foreach ($comments as $c)
                    <div class="border p-3 rounded-lg">
                        <p class="font-semibold">{{ $c->user->name }} さんより</p>
                        <p class="text-gray-700 mt-1">{{ $c->comment }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">コメントはまだありません。</p>
        @endif
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
<script src="/js/bookmark.js"></script>
@endsection