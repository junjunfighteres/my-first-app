@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow text-center">

    <h1 class="text-2xl font-bold mb-4">📨 報告が完了しました</h1>

    <p class="mb-6">運営が内容を確認します。</p>

    <button onclick="window.location='{{ route('events.index') }}'"
        class="px-6 py-3 bg-blue-600 text-black rounded-lg shadow hover:bg-blue-700 transition">
        メインページに戻る
    </button>

</div>
@endsection