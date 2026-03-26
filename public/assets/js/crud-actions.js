(() => {
  const html = document.documentElement;

  if (typeof window.toggleTheme !== 'function') {
    window.toggleTheme = function toggleTheme() {
      const isLight = html.classList.toggle('light');
      localStorage.setItem('theme', isLight ? 'light' : 'dark');
    };
  }

  document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('theme') === 'light') {
      html.classList.add('light');
    }

    const deleteModal = document.querySelector('[data-crud-delete-modal]');
    const deleteForm = deleteModal?.querySelector('[data-crud-delete-form]');
    const deleteEntity = deleteModal?.querySelector('[data-crud-delete-entity]');
    const deleteMessage = deleteModal?.querySelector('[data-crud-delete-message]');
    const deleteClosers = document.querySelectorAll('[data-crud-delete-close]');

    const closeDeleteModal = () => {
      deleteModal?.classList.remove('open');
    };

    document.addEventListener('click', event => {
      const trigger = event.target.closest('[data-crud-delete-trigger]');
      if (!trigger) {
        return;
      }

      const source = trigger.closest('[data-crud-item]') || trigger;
      const entityName = source.dataset.deleteEntity || 'this item';
      const formAction = source.dataset.deleteAction || trigger.dataset.deleteAction || '';
      const message = source.dataset.deleteMessage || trigger.dataset.deleteMessage || '';

      if (deleteEntity) {
        deleteEntity.textContent = entityName;
      }

      if (deleteMessage && message) {
        deleteMessage.textContent = message;
      }

      if (deleteForm && formAction) {
        deleteForm.action = formAction;
      }

      deleteModal?.classList.add('open');
    });

    deleteClosers.forEach(button => {
      button.addEventListener('click', closeDeleteModal);
    });

    deleteModal?.addEventListener('click', event => {
      if (event.target === deleteModal) {
        closeDeleteModal();
      }
    });

    document.querySelectorAll('[data-check-all-target]').forEach(button => {
      button.addEventListener('click', () => {
        const target = document.querySelector(button.dataset.checkAllTarget);
        target?.querySelectorAll('input[type="checkbox"]').forEach(input => {
          input.checked = true;
        });
      });
    });

    document.querySelectorAll('[data-clear-all-target]').forEach(button => {
      button.addEventListener('click', () => {
        const target = document.querySelector(button.dataset.clearAllTarget);
        target?.querySelectorAll('input[type="checkbox"]').forEach(input => {
          input.checked = false;
        });
      });
    });
  });
})();
