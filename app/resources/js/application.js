document.addEventListener('DOMContentLoaded', function () {
  console.log('application.js loaded');

  const buttons = document.querySelectorAll('[id^="apply-btn"]');
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  buttons.forEach(btn => {
    btn.addEventListener('click', async (e) => {
      e.preventDefault();

      const eventId = btn.dataset.eventId;
      const isJoined = btn.classList.contains('joined');

      if (isJoined && !confirm('参加をキャンセルしますか？')) return;

      const url = isJoined ? `/ajax/applications/${eventId}` : `/ajax/applications`;
      const method = isJoined ? 'DELETE' : 'POST';

      try {
        const res = await fetch(url, {
          method,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
          },
          body: JSON.stringify({ event_id: eventId }),
        });

        const data = await res.json();

        if (data.status === 'joined') {
          btn.classList.add('joined');
          btn.textContent = '参加をキャンセル';
        } else if (data.status === 'canceled') {
          btn.classList.remove('joined');
          btn.textContent = '参加する';
        }
      } catch (err) {
        console.error(err);
      }
    });
  });
});