@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-6">🎉 新規イベント作成</h1>

    <form action="{{ route('host.events.store.confirm') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="font-semibold">タイトル</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-4">
            <label>開催日</label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <div class="mb-4 flex gap-3">
            <div>
                <label>開始時刻</label>
                <input type="time" name="start_time" class="form-control" required>
            </div>
            <div>
                <label>終了時刻</label>
                <input type="time" name="end_time" class="form-control" required>
            </div>
        </div>

        <div class="mb-4">
            <label>開催形式</label>
            <select name="format" class="form-select" required>
                <option value="Twitch">Twitch</option>
                <option value="YouTube">YouTube</option>
                <option value="その他">その他</option>
            </select>
        </div>

        <div class="mb-4">
            <label>定員</label>
            <input type="number" name="capacity" class="form-control" value="50" required>
        </div>

        <div class="mb-4">
            <label>イベント画像</label><br>
            <input type="file" name="image">
        </div>

        <div class="mb-4">
            <label>公開設定</label>
            <select name="status" class="form-select">
                <option value="public">公開</option>
                <option value="private">非公開</option>
            </select>
        </div>

        <div class="mb-4">
            <label>説明</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('events.index') }}" class="text-blue-600 hover:underline">キャンセル</a>
            <button class="px-4 py-2 bg-blue-600 text-balck rounded">
                入力内容を確認する
            </button>
        </div>

    </form>
</div>
@endsection