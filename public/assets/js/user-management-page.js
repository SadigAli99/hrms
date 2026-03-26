document.addEventListener('DOMContentLoaded', () => {
  const userModal = document.getElementById('userModal');
  const deleteUserModal = document.getElementById('deleteUserModal');
  const roleModal = document.getElementById('roleModal');
  const openUserButtons = document.querySelectorAll('[data-action="open-user-modal"]');
  const closeUserButtons = document.querySelectorAll('[data-action="close-user-modal"]');
  const openDeleteButtons = document.querySelectorAll('[data-action="open-delete-user-modal"]');
  const closeDeleteButtons = document.querySelectorAll('[data-action="close-delete-user-modal"]');
  const openRoleButtons = document.querySelectorAll('[data-action="open-role-modal"]');
  const closeRoleButtons = document.querySelectorAll('[data-action="close-role-modal"]');
  const searchInput = document.getElementById('userSearch');
  const roleFilter = document.getElementById('userRoleFilter');
  const statusFilter = document.getElementById('userStatusFilter');
  const userRows = Array.from(document.querySelectorAll('[data-user-row]'));
  const deleteUserName = document.getElementById('deleteUserName');
  const confirmDeleteButton = document.querySelector('[data-action="confirm-delete-user"]');
  const grantAllButton = document.querySelector('[data-action="check-all-permissions"]');
  const clearAllButton = document.querySelector('[data-action="clear-all-permissions"]');
  const permissionInputs = document.querySelectorAll('#rolePermissionForm input[type="checkbox"]');
  let selectedUserRow = null;

  const bindModal = (modal, openers, closers) => {
    openers.forEach(button => button.addEventListener('click', () => modal?.classList.add('open')));
    closers.forEach(button => button.addEventListener('click', () => modal?.classList.remove('open')));
    modal?.addEventListener('click', event => {
      if (event.target === modal) {
        modal.classList.remove('open');
      }
    });
  };

  bindModal(userModal, openUserButtons, closeUserButtons);
  bindModal(roleModal, openRoleButtons, closeRoleButtons);
  bindModal(deleteUserModal, [], closeDeleteButtons);

  openDeleteButtons.forEach(button => {
    button.addEventListener('click', event => {
      selectedUserRow = event.currentTarget.closest('[data-user-row]');
      if (!selectedUserRow || !deleteUserModal) {
        return;
      }

      if (deleteUserName) {
        deleteUserName.textContent = selectedUserRow.dataset.name || 'this user';
      }

      deleteUserModal.classList.add('open');
    });
  });

  confirmDeleteButton?.addEventListener('click', () => {
    if (selectedUserRow) {
      selectedUserRow.remove();
      selectedUserRow = null;
    }

    deleteUserModal?.classList.remove('open');
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

  grantAllButton?.addEventListener('click', () => {
    permissionInputs.forEach(input => {
      input.checked = true;
    });
  });

  clearAllButton?.addEventListener('click', () => {
    permissionInputs.forEach(input => {
      input.checked = false;
    });
  });
});
