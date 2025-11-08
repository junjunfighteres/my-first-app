@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-6">✏️ イベント編集</h1>

    <form action="{{ route('host.events.update.confirm') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="event_id" value="{{ $event->id }}">
        <input type="hidden" name="current_image" value="{{ $event->image_path }}">

        <div class="mb-4">
            <label>タイトル</label>
            <input type="text" name="title" class="form-control" value="{{ $event->title }}" required>
        </div>

        <div class="mb-4">
            <label>開催日</label>
            <input type="date" name="date" class="form-control" value="{{ $event->date }}" required>
        </div>

        <div class="mb-4 flex gap-3">
            <div>
                <label>開始時刻</label>
                <input type="time" name="start_time" class="form-control" value="{{ $event->start_time }}" required>
            </div>
            <div>
                <label>終了時刻</label>
                <input type="time" name="end_time" class="form-control" value="{{ $event->end_time }}" required>
            </div>
        </div>

        <div class="mb-4">
            <label>開催形式</label>
            <select name="format" class="form-select">
                <option value="Twitch"  @if($event->format=='Twitch') selected @endif>Twitch</option>
                <option value="YouTube" @if($event->format=='YouTube') selected @endif>YouTube</option>
                <option value="その他"   @if($event->format=='その他') selected @endif>その他</option>
            </select>
        </div>

        <div class="mb-4">
            <label>定員</label>
            <input type="number" name="capacity" class="form-control" value="{{ $event->capacity }}" required>
        </div>

        <div class="mb-4">
            <label>現在の画像</label><br>
            @if ($event->image_path)
                <img src="{{ asset('storage/' . $event->image_path) }}" width="200" class="rounded shadow mb-2">
            @else
                <p>画像なし</p>
            @endif
            <input type="file" name="image">
        </div>

        <div class="mb-4">
            <label>公開設定</label>
            <select name="status" class="form-select">
                <option value="public"  @if($event->status=='public') selected @endif>公開</option>
                <option value="private" @if($event->status=='private') selected @endif>非公開</option>
            </select>
        </div>

        <div class="mb-4">
            <label>説明</label>
            <textarea name="description" class="form-control">{{ $event->description }}</textarea>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('host.events.show', $event->id) }}" class="text-blue-600 hover:underline">キャンセル</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">確認する</button>
        </div>

    </form>

</div>
@endsection