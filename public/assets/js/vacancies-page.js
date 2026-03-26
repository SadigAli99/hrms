document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('vacancySearch');
  const departmentFilter = document.getElementById('vacancyDepartment');
  const statusFilter = document.getElementById('vacancyStatus');
  const cards = Array.from(document.querySelectorAll('[data-vacancy-card]'));

  if (!searchInput || !departmentFilter || !statusFilter || !cards.length) {
    return;
  }

  const applyFilters = () => {
    const query = searchInput.value.trim().toLowerCase();
    const department = departmentFilter.value;
    const status = statusFilter.value;

    cards.forEach(card => {
      const haystack = [
        card.dataset.title,
        card.dataset.department,
        card.dataset.location
      ].join(' ').toLowerCase();
      const matchesQuery = !query || haystack.includes(query);
      const matchesDepartment = department === 'all' || card.dataset.department === department;
      const matchesStatus = status === 'all' || card.dataset.status === status;
      card.hidden = !(matchesQuery && matchesDepartment && matchesStatus);
    });
  };

  searchInput.addEventListener('input', applyFilters);
  departmentFilter.addEventListener('change', applyFilters);
  statusFilter.addEventListener('change', applyFilters);
});
