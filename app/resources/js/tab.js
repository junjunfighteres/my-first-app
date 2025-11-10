// ページのHTMLがすべて読み込まれてから実行
document.addEventListener('DOMContentLoaded', () => {
  // タブボタン（参加済み・ブックマーク・主催イベント）を取得
  const tabs = document.querySelectorAll('.tab');
  // イベント一覧を入れるコンテナ
  const eventList = document.getElementById('event-list');

  // 安全対策
  if (!tabs.length || !eventList) return;

  // タブクリック時の処理
  tabs.forEach(tab => {
    tab.addEventListener('click', async (e) => {
      e.preventDefault();

      // クリックされたタブタイプ（joined/bookmarked/hosted）
      const type = tab.dataset.type;

      // activeクラス制御
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');

      // ローディング中表示
      eventList.innerHTML = '<p class="text-muted p-3">読み込み中...</p>';

      try {
        // Ajaxルートにアクセス（例: /ajax/tabs/hosted）
        const response = await fetch(`/ajax/tabs/${type}`, {
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!response.ok) {
          throw new Error('通信に失敗しました');
        }

        // 取得したHTMLをそのまま反映
        const html = await response.text();
        eventList.innerHTML = html;

      } catch (error) {
        console.error(error);
        eventList.innerHTML = '<p class="text-danger p-3">データの取得に失敗しました。</p>';
      }
    });
  });
});