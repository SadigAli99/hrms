document.addEventListener('DOMContentLoaded', () => {
  const tagInput = document.getElementById('requirementInput');
  const tagType = document.getElementById('requirementType');
  const tagRequired = document.getElementById('requirementRequired');
  const addButton = document.getElementById('addRequirementButton');
  const list = document.getElementById('requirementList');
  const hiddenInput = document.getElementById('vacancyRequirementsPayload');
  const form = document.getElementById('vacancyForm');

  if (!tagInput || !tagType || !tagRequired || !addButton || !list || !hiddenInput || !form) {
    return;
  }

  const syncPayload = () => {
    const items = Array.from(list.querySelectorAll('[data-requirement-item]')).map(item => ({
      label: item.dataset.label,
      type: item.dataset.type,
      required: item.dataset.required === 'true'
    }));
    hiddenInput.value = JSON.stringify(items);
  };

  const removeHandlers = () => {
    list.querySelectorAll('[data-remove-requirement]').forEach(button => {
      button.onclick = () => {
        button.closest('[data-requirement-item]')?.remove();
        syncPayload();
      };
    });
  };

  const appendTag = (label, type, required) => {
    const value = label.trim();
    if (!value) {
      return;
    }
    const duplicate = Array.from(list.querySelectorAll('[data-requirement-item]')).some(item =>
      item.dataset.label.toLowerCase() === value.toLowerCase()
    );
    if (duplicate) {
      return;
    }

    list.insertAdjacentHTML('beforeend', `
      <div class="requirement-pill ${required ? 'is-required' : 'is-optional'}" data-requirement-item data-label="${value}" data-type="${type}" data-required="${required}">
        <div>
          <strong>${value}</strong>
          <span>${type} / ${required ? 'required' : 'preferred'}</span>
        </div>
        <button type="button" data-remove-requirement>Remove</button>
      </div>
    `);
    tagInput.value = '';
    removeHandlers();
    syncPayload();
  };

  addButton.addEventListener('click', () => appendTag(tagInput.value, tagType.value, tagRequired.checked));
  tagInput.addEventListener('keydown', event => {
    if (event.key === 'Enter') {
      event.preventDefault();
      appendTag(tagInput.value, tagType.value, tagRequired.checked);
    }
  });

  document.querySelectorAll('[data-preset]').forEach(button => {
    button.addEventListener('click', () => appendTag(button.dataset.preset, button.dataset.type, true));
  });

  form.addEventListener('submit', () => {
    syncPayload();
  });

  removeHandlers();
  syncPayload();
});
