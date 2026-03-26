document.addEventListener('DOMContentLoaded', () => {
  const reuseModal = document.getElementById('talentReuseModal');
  const openButtons = document.querySelectorAll('[data-action="open-reuse-modal"]');
  const closeButtons = document.querySelectorAll('[data-action="close-talent-reuse-modal"]');
  const searchInput = document.getElementById('talentSearch');
  const categoryFilter = document.getElementById('talentCategory');
  const cards = Array.from(document.querySelectorAll('[data-talent-card]'));

  openButtons.forEach(button => button.addEventListener('click', () => {
    reuseModal?.classList.add('open');
  }));

  closeButtons.forEach(button => button.addEventListener('click', () => reuseModal?.classList.remove('open')));
  reuseModal?.addEventListener('click', event => {
    if (event.target === reuseModal) {
      reuseModal.classList.remove('open');
    }
  });

  if (!searchInput || !categoryFilter || !cards.length) {
    return;
  }

  const applyFilters = () => {
    const query = searchInput.value.trim().toLowerCase();
    const category = categoryFilter.value;

    cards.forEach(card => {
      const haystack = [card.dataset.name, card.dataset.role, card.dataset.skills].join(' ').toLowerCase();
      const matchesQuery = !query || haystack.includes(query);
      const matchesCategory = category === 'all' || card.dataset.category === category;
      card.hidden = !(matchesQuery && matchesCategory);
    });
  };

  searchInput.addEventListener('input', applyFilters);
  categoryFilter.addEventListener('change', applyFilters);
});
