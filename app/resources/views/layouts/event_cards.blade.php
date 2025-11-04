@if(count($events) > 0)
  <div class="event-cards">
    @foreach ($events as $event)
      <div class="event-card">
        <h3>{{ $event->title }}</h3>
        <p>📅 {{ $event->date }} {{ $event->start_time }}</p>
        <p>📺 {{ $event->format }}</p>
        <a href="{{ route('user_main', $event->id) }}">詳細を見る</a>
      </div>
    @endforeach
  </div>
@else
  <p>イベントが見つかりません。</p>
@endif