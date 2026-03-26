const html = document.documentElement;

function toggleTheme() {
  const isLight = html.classList.toggle('light');
  localStorage.setItem('theme', isLight ? 'light' : 'dark');
}

document.addEventListener('DOMContentLoaded', () => {
  if (localStorage.getItem('theme') === 'light') {
    html.classList.add('light');
  }

  const deleteUserModal = document.getElementById('deleteUserModal');
  const deleteUserName = document.getElementById('deleteUserName');
  const deleteUserForm = document.getElementById('deleteUserForm');
  const openDeleteButtons = document.querySelectorAll('[data-action="open-delete-user-modal"]');
  const closeDeleteButtons = document.querySelectorAll('[data-action="close-delete-user-modal"]');
  const searchInput = document.getElementById('userSearch');
  const roleFilter = document.getElementById('userRoleFilter');
  const statusFilter = document.getElementById('userStatusFilter');
  const userRows = Array.from(document.querySelectorAll('[data-user-row]'));

  const closeDeleteModal = () => {
    deleteUserModal?.classList.remove('open');
  };

  openDeleteButtons.forEach(button => {
    button.addEventListener('click', event => {
      const row = event.currentTarget.closest('[data-user-row]');
      if (!row || !deleteUserModal) {
        return;
      }

      if (deleteUserName) {
        deleteUserName.textContent = row.dataset.name || 'this user';
      }

      if (deleteUserForm && row.dataset.deleteUrl) {
        deleteUserForm.action = row.dataset.deleteUrl;
      }

      deleteUserModal.classList.add('open');
    });
  });

  closeDeleteButtons.forEach(button => {
    button.addEventListener('click', closeDeleteModal);
  });

  deleteUserModal?.addEventListener('click', event => {
    if (event.target === deleteUserModal) {
      closeDeleteModal();
    }
  });

  if (searchInput && roleFilter && statusFilter && userRows.length) {
    const applyFilters = () => {
      const query = searchInput.value.trim().toLowerCase();
      const role = roleFilter.value;
      const status = statusFilter.value;

      userRows.forEach(row => {
        const haystack = [row.dataset.name, row.dataset.email, row.dataset.role].join(' ').toLowerCase();
        const matchesQuery = !query || haystack.includes(query);
        const matchesRole = role === 'all' || row.dataset.role === role;
        const matchesStatus = status === 'all' || row.dataset.status === status;
        row.hidden = !(matchesQuery && matchesRole && matchesStatus);
      });
    };

    searchInput.addEventListener('input', applyFilters);
    roleFilter.addEventListener('change', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
  }
});
