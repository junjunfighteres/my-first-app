@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow text-center">

    {{-- ヘッダー --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">イベント非表示完了</h1>
    </div>

    <h2 class="text-xl font-semibold mb-4">対象イベントを非表示にしました。</h2>

    <div class="mb-4">
        <p class="font-semibold">イベントID</p>
        <p class="border p-2 rounded bg-gray-100 inline-block">{{ $event->id }}</p>
    </div>

    <div class="mb-4">
        <p class="font-semibold">タイトル</p>
        <p class="border p-2 rounded bg-gray-100 inline-block">{{ $event->title }}</p>
    </div>

    <div class="space-x-4 mt-6">
        <a href="{{ route('admin.events.show', $event->id) }}" 
           class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
            イベント詳細に戻る
        </a>

        <a href="{{ route('admin.events.index') }}" 
           class="px-4 py-2 bg-blue-500 text-black rounded hover:bg-blue-600">
            イベント管理一覧に戻る
        </a>
    </div>
</div>
@endsection