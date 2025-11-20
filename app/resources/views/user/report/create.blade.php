@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-4">🚨 違反報告</h1>

    <p class="mb-4">
        以下のイベントについて運営に報告します。
    </p>

    <div class="border p-4 rounded mb-4 bg-gray-50">
        <p><strong>イベント名：</strong> {{ $event->title }}</p>
        <p><strong>主催者：</strong> {{ $event->user->name }}</p>
        <p><strong>日付：</strong> {{ $event->date }}</p>
    </div>

    <form action="{{ route('report.store', $event->id) }}" method="POST">
        @csrf

        <label class="font-semibold">報告理由（必須）</label>
        <textarea name="reason" class="w-full border rounded p-3 mb-4" rows="5"
                  placeholder="どのような問題があったかご記入ください">{{ old('reason') }}</textarea>

        <div class="flex justify-between">
            <a href="{{ url()->previous() }}" class="text-gray-600 hover:underline">戻る</a>
            <button class="px-4 py-2 bg-red-500 text-black rounded">報告する</button>
        </div>
    </form>

</div>
@endsection