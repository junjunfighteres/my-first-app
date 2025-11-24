@if(count($events) > 0)
  <div class="event-cards">
    @foreach ($events as $event)
      <div class="event-card">
        <h3>{{ $event->title }}</h3>
        <p class="text-gray-600 text-sm">
          主催者：
          <a href="{{ route('user.profile.other', $event->user->id) }}"
            class="text-blue-500 underline">
            {{ $event->user->name ?? '不明' }}
          </a>
        </p>
        <p>📅 {{ $event->date }} {{ $event->start_time }}</p>
        <p>📺 {{ $event->format }}</p>
        <a href="{{ route('user.events.show', $event->id) }}">詳細を見る</a>
      </div>
    @endforeach
  </div>
@else
  <p>イベントが見つかりません。</p>
@endif