@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6 mt-10">
    <h2 class="text-xl font-bold border-b pb-2 mb-4">
        イベント名：{{ $event->title }}
    </h2>

    <div class="space-y-3 text-sm">
        <div>
            <span class="font-semibold">主催者名：</span>
            <span>{{ $event->organizer ?? '未設定' }}</span>
        </div>

        <div>
            <span class="font-semibold">開催日：</span>
            <span>{{ $event->start_date }} 〜 {{ $event->end_date }}</span>
        </div>

        <div>
            <span class="font-semibold">時間：</span>
            <span>{{ $event->start_time }} 〜 {{ $event->end_time }}</span>
        </div>

        <div>
            <span class="font-semibold">配信形式：</span>
            <span>{{ $event->platform ?? '未設定' }}</span>
        </div>

        <div>
            <span class="font-semibold">定員：</span>
            <span>{{ $event->capacity }}名</span>
        </div>

        <div>
            <span class="font-semibold">現在参加人数：</span>
            <span>{{ $event->current_participants ?? 0 }}名</span>
        </div>
    </div>

    <hr class="my-6">

    <form action="{{ route('user.apply.confirm', ['event_id' => $event->id]) }}" method="POST">
        @csrf
        <h3 class="font-semibold mb-3 text-lg">参加申込内容確認</h3>

        <div class="mb-3">
            <label class="block text-sm font-medium">ユーザー名</label>
            <input type="text" class="border rounded w-full p-2 bg-gray-100" value="{{ $user->name }}" readonly>
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">メールアドレス</label>
            <input type="text" class="border rounded w-full p-2 bg-gray-100" value="{{ $user->email }}" readonly>
        </div>

        <div class="mb-3">
            <label class="block text-sm font-medium">コメント（任意）</label>
            <textarea name="comment" class="border rounded w-full p-2" rows="3" placeholder="一言コメントなど">{{ old('comment') }}</textarea>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ url()->previous() }}" class="bg-gray-300 text-gray-800 py-2 px-4 rounded hover:bg-gray-400">
                戻る
            </a>
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                入力内容を確認する
            </button>
        </div>
    </form>
</div>
@endsection