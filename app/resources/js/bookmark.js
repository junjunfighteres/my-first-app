document.addEventListener('DOMContentLoaded', function () {
  console.log('bookmark.js loaded');

  const buttons = document.querySelectorAll('[id^="bookmark-btn"]');
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  console.log('bookmark buttons found:', buttons.length);

  buttons.forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault();

      const eventId = btn.dataset.eventId;
      const isBookmarked = btn.classList.contains('bookmarked');
      console.log(`clicked bookmark button for event ${eventId}, current state: ${isBookmarked ? 'bookmarked' : 'unbookmarked'}`);

      try {
        const url = isBookmarked
          ? `/ajax/bookmarks/${eventId}`
          : `/ajax/bookmarks`;

        const method = isBookmarked ? 'DELETE' : 'POST';

        const res = await fetch(url, { // ← ここでurlを使う！
          method: method,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
          },
          body: JSON.stringify({ event_id: eventId })
        });

        console.log('fetch done:', res.status);

        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();

        // 見た目更新
        if (data.status === 'bookmarked') {
          btn.classList.add('bookmarked');
          btn.textContent = '★ ブックマーク中';
        } else if (data.status === 'unbookmarked') {
          btn.classList.remove('bookmarked');
          btn.textContent = '☆ ブックマーク';
        }

      } catch (error) {
        console.error('fetch error:', error);
      }
    });
  });
});