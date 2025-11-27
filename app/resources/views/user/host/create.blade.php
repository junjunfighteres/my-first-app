@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-6">🎉 新規イベント作成</h1>

    {{-- ★ 追加：全体のエラー一覧（上部にまとめて表示） --}}
    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-danger rounded">
            <strong>入力内容にエラーがあります：</strong>
            <ul class="list-disc ml-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('host.events.store.confirm') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- タイトル --}}
        <div class="mb-4">
            <label class="font-semibold">タイトル</label>
            <input type="text" name="title" class="form-control"
                   value="{{ old('title') }}">
            @error('title')
                <p class="text-danger text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- 開催日 --}}
        <div class="mb-4">
            <label>開催日</label>
            <input type="date" name="date" class="form-control"
                   value="{{ old('date') }}">
            @error('date')
                <p class="text-danger text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- 開始・終了時間 --}}
        <div class="mb-4 flex gap-3">
            <div>
                <label>開始時刻</label>
                <input type="time" name="start_time" class="form-control"
                       value="{{ old('start_time') }}">
                @error('start_time')
                    <p class="text-danger text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label>終了時刻</label>
                <input type="time" name="end_time" class="form-control"
                       value="{{ old('end_time') }}">
                @error('end_time')
                    <p class="text-danger text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- イベント種別 --}}
        <div class="col-md-3">
            <label class="form-label">イベント種別</label>
            <select name="format" class="form-select">
                <option value="">選択してください</option>
                <option value="meeting"  {{ old('format')=='meeting' ? 'selected':'' }}>オンラインミーティング</option>
                <option value="seminar"  {{ old('format')=='seminar' ? 'selected':'' }}>セミナー</option>
                <option value="workshop" {{ old('format')=='workshop'? 'selected':'' }}>ワークショップ</option>
                <option value="sports"   {{ old('format')=='sports'  ? 'selected':'' }}>スポーツイベント</option>
                <option value="party"    {{ old('format')=='party'   ? 'selected':'' }}>交流会</option>
            </select>
            @error('format')
                <p class="text-danger text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- 定員 --}}
        <div class="mb-4 mt-3">
            <label>定員</label>
            <input type="number" name="capacity" class="form-control"
                   value="{{ old('capacity', 50) }}">
            @error('capacity')
                <p class="text-danger text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- 画像 --}}
        <div class="mb-3">
            <label class="form-label">イベント画像</label>
            <input type="file" name="image" class="form-control">
            @error('image')
                <p class="text-danger text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- 公開設定 --}}
        <div class="mb-4">
            <label>公開設定</label>
            <select name="status" class="form-select">
                <option value="public"  {{ old('status')=='public' ? 'selected':'' }}>公開</option>
                <option value="private" {{ old('status')=='private'? 'selected':'' }}>非公開</option>
            </select>
            @error('status')
                <p class="text-danger text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- 説明 --}}
        <div class="mb-4">
            <label>説明</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-danger text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ボタン --}}
        <div class="flex justify-between mt-6">
            <a href="{{ route('events.index') }}" class="text-blue-600 hover:underline">キャンセル</a>

            <button class="px-4 py-2 bg-blue-600 text-black rounded">
                入力内容を確認する
            </button>
        </div>

    </form>
</div>
@endsection
