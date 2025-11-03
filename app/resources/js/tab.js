document.addEventListener('DOMContentLoaded', function() {
  const tabs = document.querySelectorAll('.tab');

  tabs.forEach(tab => {
    tab.addEventListener('click', function() {
      const type = this.dataset.type; // 例: "hosted"

      fetch(`/events/${type}`)
        .then(response => response.text())
        .then(html => {
          document.getElementById('event-list').innerHTML = html;
        })
        .catch(error => console.error('Error:', error));
    });
  });
});
