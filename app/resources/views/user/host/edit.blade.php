@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-6">✏️ イベント編集</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>入力エラーがあります：</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
        </div>
    @endif

    <form action="{{ route('host.events.update.confirm') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        {{-- イベントID --}}
        <input type="hidden" name="event_id" value="{{ $event->id }}">

        {{-- 現在の画像パス --}}
        <input type="hidden" name="current_image" value="{{ $event->image_path }}">

        {{-- タイトル --}}
        <div class="mb-4">
            <label class="font-semibold">タイトル</label>
            <input type="text" name="title" class="form-control"
                   value="{{ e($event->title) }}" required>
        </div>

        {{-- 開催日 --}}
        <div class="mb-4">
            <label class="font-semibold">開催日</label>
            <input type="date" name="date" class="form-control"
                   value="{{ $event->date }}" required>
        </div>

        {{-- 時間 --}}
        <div class="mb-4 flex gap-3">
            <div>
                <label class="font-semibold">開始時刻</label>
                <input type="time" name="start_time" class="form-control"
                       value="{{ $event->start_time }}" required>
            </div>
            <div>
                <label class="font-semibold">終了時刻</label>
                <input type="time" name="end_time" class="form-control"
                       value="{{ $event->end_time }}" required>
            </div>
        </div>

        {{-- ⭐ 開催形式（会社用カテゴリ） --}}
        <div class="mb-4">
            <label class="font-semibold">イベント種別</label>
            <select name="format" class="form-select">

                <option value="meeting"  {{ $event->format == 'meeting'  ? 'selected' : '' }}>オンラインミーティング</option>
                <option value="seminar"  {{ $event->format == 'seminar'  ? 'selected' : '' }}>セミナー</option>
                <option value="workshop" {{ $event->format == 'workshop' ? 'selected' : '' }}>ワークショップ</option>
                <option value="sports"   {{ $event->format == 'sports'   ? 'selected' : '' }}>スポーツイベント</option>
                <option value="party"    {{ $event->format == 'party'    ? 'selected' : '' }}>交流会</option>

            </select>
        </div>

        {{-- 定員 --}}
        <div class="mb-4">
            <label class="font-semibold">定員</label>
            <input type="number" name="capacity" class="form-control"
                   value="{{ $event->capacity }}" required>
        </div>

        {{-- 画像 --}}
        <div class="mb-4">
            <label class="font-semibold">現在の画像</label><br>
            @if ($event->image_path)
                <img src="{{ asset('storage/' . $event->image_path) }}"
                     width="200" class="rounded shadow mb-2">
            @else
                <p class="text-gray-500">画像なし</p>
            @endif

            <input type="file" name="image" accept="image/*" class="form-control mt-2">
        </div>

        {{-- 公開設定 --}}
        <div class="mb-4">
            <label class="font-semibold">公開設定</label>
            <select name="status" class="form-select">
                <option value="public"  {{ $event->status == 'public'  ? 'selected' : '' }}>公開</option>
                <option value="private" {{ $event->status == 'private' ? 'selected' : '' }}>非公開</option>
            </select>
        </div>

        {{-- 説明 --}}
        <div class="mb-4">
            <label class="font-semibold">説明</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $event->description) }}</textarea>
        </div>

        {{-- ボタン --}}
        <div class="flex justify-between mt-6">
            <a href="{{ route('host.events.show', $event->id) }}"
               class="text-blue-600 hover:underline">
                キャンセル
            </a>

            <button class="px-4 py-2 bg-blue-600 text-black rounded">
                確認する
            </button>
        </div>

    </form>

</div>
@endsection
