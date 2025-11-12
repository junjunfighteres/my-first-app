console.log('tab.js loaded (start)');

document.addEventListener('DOMContentLoaded', function() {
  console.log('DOMContentLoaded event fired');

  const tabs = document.querySelectorAll('.tab');
  const eventList = document.getElementById('event-list');
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  console.log('tabs found:', tabs.length);
  console.log('eventList found:', !!eventList);

  if (!tabs.length || !eventList) {
    console.warn('タブまたはイベントリストが見つかりません');
    return;
  }

  tabs.forEach(tab => {
    tab.addEventListener('click', async (e) => {
      e.preventDefault();
      const type = tab.dataset.type;
      console.log(`clicked tab: ${type}`);

      // active クラス切り替え
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');

      // ローディング表示
      eventList.innerHTML = '<p class="text-muted p-3">読み込み中...</p>';

      try {
        const response = await fetch(`/ajax/tabs/${type}`, {
          method: 'GET',
          headers: { 'X-Requested-With': 'XMLHttpRequest',
                     'X-CSRF-TOKEN': token
      },
      credentials: 'same-origin' // ← これでCookieも送られる
      });

        console.log('fetch done:', response.status);

        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const html = await response.text();
        eventList.innerHTML = html.trim()
          ? html
          : '<p class="text-muted p-3">イベントが見つかりません。</p>';
      } catch (err) {
        console.error('fetch error:', err);
        eventList.innerHTML = '<p class="text-danger p-3">読み込み失敗</p>';
      }
    });
  });
});