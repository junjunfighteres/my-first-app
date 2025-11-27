{{-- layouts/event_cards.blade.php --}}

<div class="row g-4">
    @foreach ($events as $event)
        <div class="col-12 col-sm-6 col-md-4">
            <a href="{{ route('user.events.show', $event->id) }}" class="text-decoration-none text-dark">
                <div class="card shadow-sm h-100">

                    {{-- ▲ サムネイル --}}
                    @if (!empty($event->image_path))
                        <img src="{{ asset('storage/' . $event->image_path) }}"
                             class="card-img-top"
                             style="height: 180px; object-fit: cover;">
                    @else
                        <img src="{{ asset('img/default-event.jpg') }}"
                             class="card-img-top"
                             style="height: 180px; object-fit: cover; opacity:0.9;">
                    @endif

                    <div class="card-body">

                        {{-- タイトル --}}
                        <h5 class="card-title fw-bold mb-2">
                            {{ $event->title }}
                        </h5>

                        {{-- 開催日 --}}
                        <p class="card-text text-muted mb-1">
                            📅 {{ $event->start_date }}  
                            {{ $event->start_time }}〜{{ $event->end_time }}
                        </p>

                        {{-- イベント種別（meeting / seminar / sports etc） --}}
                        <p class="mb-1">
                            🏷 詳細画面へ{{ ucfirst($event->category) ?? '未分類' }}
                        </p>

                        {{-- 参加人数 --}}
                        <p class="text-muted mb-0">
                            👥 {{ $event->applications_count ?? 0 }} / {{ $event->capacity }} 名
                        </p>

                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>