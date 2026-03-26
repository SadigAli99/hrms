document.addEventListener('DOMContentLoaded', () => {
  const input = document.querySelector('[data-profile-image-input]');
  const preview = document.querySelector('[data-profile-image-preview]');
  const placeholder = document.querySelector('[data-profile-image-placeholder]');
  const fileName = document.querySelector('[data-profile-image-name]');
  const deleteTrigger = document.querySelector('[data-profile-image-delete-trigger]');
  const deleteModal = document.querySelector('[data-profile-image-delete-modal]');
  const deleteClosers = document.querySelectorAll('[data-profile-image-delete-close]');
  const deleteConfirm = document.querySelector('[data-profile-image-delete-confirm]');
  const csrfToken = document.querySelector('input[name="_token"]')?.value || '';

  if (!input) {
    return;
  }

  const toggleDeleteTrigger = show => {
    if (!deleteTrigger) {
      return;
    }

    deleteTrigger.classList.toggle('hidden', !show);
    deleteTrigger.classList.toggle('inline-flex', show);
  };

  const clearImagePreview = () => {
    if (preview) {
      preview.src = '';
      preview.classList.add('hidden');
    }

    placeholder?.classList.remove('hidden');
    toggleDeleteTrigger(false);

    if (input) {
      input.value = '';
    }

    if (fileName) {
      fileName.textContent = 'No file selected';
    }
  };

  const closeDeleteModal = () => {
    deleteModal?.classList.remove('open');
  };

  input.addEventListener('change', event => {
    const [file] = event.target.files || [];

    if (!file) {
      clearImagePreview();
      return;
    }

    if (fileName) {
      fileName.textContent = file.name;
    }

    if (!file.type.startsWith('image/')) {
      return;
    }

    const reader = new FileReader();
    reader.onload = loadEvent => {
      if (!preview) {
        return;
      }

      preview.src = loadEvent.target?.result || '';
      preview.classList.remove('hidden');
      placeholder?.classList.add('hidden');
      toggleDeleteTrigger(true);
    };

    reader.readAsDataURL(file);
  });

  deleteTrigger?.addEventListener('click', event => {
    event.preventDefault();
    event.stopPropagation();
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

  deleteConfirm?.addEventListener('click', async () => {
    const url = deleteConfirm.dataset.deleteUrl;
    const method = deleteConfirm.dataset.deleteMethod || 'DELETE';

    if (!url) {
      closeDeleteModal();
      clearImagePreview();
      return;
    }

    try {
      await fetch(url, {
        method,
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        },
      });

      clearImagePreview();
    } catch (error) {
      console.error(error);
    } finally {
      closeDeleteModal();
    }
  });
});
