document.addEventListener('DOMContentLoaded', () => {
  const filterInput = document.getElementById('interviewSearch');
  const cards = Array.from(document.querySelectorAll('[data-interview-card]'));

  if (!filterInput || !cards.length) {
    return;
  }

  filterInput.addEventListener('input', () => {
    const query = filterInput.value.trim().toLowerCase();
    cards.forEach(card => {
      const haystack = [card.dataset.name, card.dataset.role, card.dataset.stage].join(' ').toLowerCase();
      card.hidden = !!query && !haystack.includes(query);
    });
  });
});
