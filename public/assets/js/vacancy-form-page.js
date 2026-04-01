document.addEventListener('DOMContentLoaded', () => {
  const tagInput = document.getElementById('requirementInput');
  const tagValue = document.getElementById('requirementValue');
  const tagType = document.getElementById('requirementType');
  const tagWeight = document.getElementById('requirementWeight');
  const tagRequired = document.getElementById('requirementRequired');
  const addButton = document.getElementById('addRequirementButton');
  const list = document.getElementById('requirementList');
  const form = document.getElementById('vacancyForm');
  const closeDateDisplay = document.getElementById('vacancyCloseDateDisplay');
  const closeDateInput = document.getElementById('vacancyCloseDate');
  const datePicker = document.querySelector('[data-date-picker]');
  const datePickerPanel = document.querySelector('[data-date-picker-panel]');
  const datePickerToggle = document.querySelector('[data-date-picker-toggle]');

  if (!tagInput || !tagValue || !tagType || !tagWeight || !tagRequired || !addButton || !list || !form) {
    return;
  }

  const weekdayLabels = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];
  const monthLabels = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ];
  let calendarDate = new Date();

  const resolveDefaultRequirementWeight = type => {
    if (type === 'skill') {
      return '5';
    }

    if (type === 'education' || type === 'certification') {
      return '1';
    }

    return '3';
  };

  const normalizeDateInput = value => value.replace(/\D/g, '').slice(0, 8);

  const formatDateInput = value => {
    const digits = normalizeDateInput(value);
    const parts = [];

    if (digits.length > 0) {
      parts.push(digits.slice(0, 2));
    }
    if (digits.length > 2) {
      parts.push(digits.slice(2, 4));
    }
    if (digits.length > 4) {
      parts.push(digits.slice(4, 8));
    }

    return parts.join('-');
  };

  const toStorageDate = value => {
    const match = value.match(/^(\d{2})-(\d{2})-(\d{4})$/);
    if (!match) {
      return '';
    }

    const [, day, month, year] = match;
    return `${year}-${month}-${day}`;
  };

  const toDisplayDate = value => {
    const match = value.match(/^(\d{4})-(\d{2})-(\d{2})$/);
    if (!match) {
      return '';
    }

    const [, year, month, day] = match;
    return `${day}-${month}-${year}`;
  };

  const parseStorageDate = value => {
    const match = value.match(/^(\d{4})-(\d{2})-(\d{2})$/);
    if (!match) {
      return null;
    }

    const [, year, month, day] = match;
    const date = new Date(Number(year), Number(month) - 1, Number(day));
    return Number.isNaN(date.getTime()) ? null : date;
  };

  const sameDay = (left, right) =>
    left.getFullYear() === right.getFullYear() &&
    left.getMonth() === right.getMonth() &&
    left.getDate() === right.getDate();

  const openDatePicker = () => {
    datePickerPanel?.classList.remove('hidden');
  };

  const closeDatePicker = () => {
    datePickerPanel?.classList.add('hidden');
  };

  const renderCalendar = () => {
    if (!datePickerPanel) {
      return;
    }

    const selectedDate = parseStorageDate(closeDateInput?.value || '');
    const today = new Date();
    const year = calendarDate.getFullYear();
    const month = calendarDate.getMonth();
    const firstDay = new Date(year, month, 1);
    const startOffset = (firstDay.getDay() + 6) % 7;
    const gridStart = new Date(year, month, 1 - startOffset);
    const days = [];

    for (let index = 0; index < 42; index += 1) {
      const day = new Date(gridStart);
      day.setDate(gridStart.getDate() + index);
      const iso = `${day.getFullYear()}-${String(day.getMonth() + 1).padStart(2, '0')}-${String(day.getDate()).padStart(2, '0')}`;
      const classes = ['cal-day'];

      if (day.getMonth() !== month) {
        classes.push('muted');
      }
      if (sameDay(day, today)) {
        classes.push('today');
      }
      if (selectedDate && sameDay(day, selectedDate)) {
        classes.push('selected');
      }

      days.push(`
        <button type="button" class="${classes.join(' ')}" data-calendar-date="${iso}">
          ${day.getDate()}
        </button>
      `);
    }

    datePickerPanel.innerHTML = `
      <div class="date-picker-header">
        <button class="date-picker-nav" type="button" data-calendar-nav="prev">&lt;</button>
        <div class="date-picker-title">${monthLabels[month]} ${year}</div>
        <button class="date-picker-nav" type="button" data-calendar-nav="next">&gt;</button>
      </div>
      <div class="date-picker-weekdays">
        ${weekdayLabels.map(label => `<span>${label}</span>`).join('')}
      </div>
      <div class="date-picker-grid">
        ${days.join('')}
      </div>
    `;
  };

  const syncCloseDate = () => {
    if (!closeDateDisplay || !closeDateInput) {
      return;
    }

    closeDateDisplay.value = formatDateInput(closeDateDisplay.value);
    closeDateInput.value = toStorageDate(closeDateDisplay.value);

    const parsedDate = parseStorageDate(closeDateInput.value);
    if (parsedDate) {
      calendarDate = parsedDate;
    }

    renderCalendar();
  };

  const refreshRequirementIndexes = () => {
    Array.from(list.querySelectorAll('[data-requirement-item]')).forEach((item, index) => {
      item.querySelectorAll('[data-requirement-field]').forEach(field => {
        field.name = `vacancy_requirements[${index}][${field.dataset.requirementField}]`;
      });
    });
  };

  const hydrateRequirementForm = item => {
    tagInput.value = item.dataset.label || '';
    tagValue.value = item.dataset.value || '';
    tagType.value = item.dataset.type || tagType.value;
    tagWeight.value = item.dataset.weight ?? resolveDefaultRequirementWeight(tagType.value);
    tagRequired.checked = (item.dataset.required || 'false') === 'true';
  };

  const removeHandlers = () => {
    list.querySelectorAll('[data-edit-requirement]').forEach(button => {
      button.onclick = () => {
        const item = button.closest('[data-requirement-item]');

        if (!item) {
          return;
        }

        hydrateRequirementForm(item);
        item.remove();
        refreshRequirementIndexes();
        tagInput.focus();
      };
    });

    list.querySelectorAll('[data-remove-requirement]').forEach(button => {
      button.onclick = () => {
        button.closest('[data-requirement-item]')?.remove();
        refreshRequirementIndexes();
      };
    });
  };

  const appendTag = (label, extraValue, type, weight, required) => {
    const name = label.trim();
    const value = extraValue.trim();
    const normalizedWeight = String(weight ?? '').trim();
    const weightLabel = normalizedWeight === ''
      ? 'secilmeyib'
      : normalizedWeight === '5'
        ? 'yuksek'
        : normalizedWeight === '1'
          ? 'asagi'
          : 'orta';

    if (!name) {
      return;
    }

    const duplicate = Array.from(list.querySelectorAll('[data-requirement-item]')).some(item =>
      item.dataset.label.toLowerCase() === name.toLowerCase() &&
      (item.dataset.value || '').toLowerCase() === value.toLowerCase()
    );

    if (duplicate) {
      return;
    }

    const index = list.querySelectorAll('[data-requirement-item]').length;

    list.insertAdjacentHTML('beforeend', `
      <div class="requirement-pill ${required ? 'is-required' : 'is-optional'}" data-requirement-item data-label="${name}" data-value="${value}" data-type="${type}" data-weight="${normalizedWeight}" data-required="${required}">
        <div>
          <strong>${name}${value ? `: ${value}` : ''}</strong>
          <span>${type} / ${weightLabel} / ${required ? 'required' : 'preferred'}</span>
        </div>
        <div class="requirement-pill-actions">
          <input type="hidden" name="vacancy_requirements[${index}][label]" value="${name}" data-requirement-field="label">
          <input type="hidden" name="vacancy_requirements[${index}][value]" value="${value}" data-requirement-field="value">
          <input type="hidden" name="vacancy_requirements[${index}][type]" value="${type}" data-requirement-field="type">
          <input type="hidden" name="vacancy_requirements[${index}][weight]" value="${normalizedWeight}" data-requirement-field="weight">
          <input type="hidden" name="vacancy_requirements[${index}][required]" value="${required ? 1 : 0}" data-requirement-field="required">
          <button class="icon-action-btn tooltip" type="button" data-edit-requirement data-tip="Edit" aria-label="Edit requirement">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 14.5a4 4 0 01-1.414.94l-3.122 1.041 1.04-3.121A4 4 0 019 11z" />
            </svg>
          </button>
          <button class="icon-action-btn danger tooltip" type="button" data-remove-requirement data-tip="Remove" aria-label="Remove requirement">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12m-9 0V5a1 1 0 011-1h4a1 1 0 011 1v2m-7 0l1 12h4l1-12" />
            </svg>
          </button>
        </div>
      </div>
    `);

    tagInput.value = '';
    tagValue.value = '';
    tagWeight.value = resolveDefaultRequirementWeight(tagType.value);
    removeHandlers();
    refreshRequirementIndexes();
  };

  addButton.addEventListener('click', () => appendTag(tagInput.value, tagValue.value, tagType.value, tagWeight.value, tagRequired.checked));

  [tagInput, tagValue].forEach(input => input.addEventListener('keydown', event => {
    if (event.key === 'Enter') {
      event.preventDefault();
      appendTag(tagInput.value, tagValue.value, tagType.value, tagWeight.value, tagRequired.checked);
    }
  }));

  tagType.addEventListener('change', () => {
    tagWeight.value = resolveDefaultRequirementWeight(tagType.value);
  });

  form.addEventListener('submit', () => {
    syncCloseDate();
    refreshRequirementIndexes();
  });

  if (closeDateDisplay && closeDateInput) {
    closeDateDisplay.addEventListener('input', () => {
      syncCloseDate();
    });

    closeDateDisplay.addEventListener('blur', () => {
      syncCloseDate();
    });
  }

  datePickerToggle?.addEventListener('click', () => {
    if (datePickerPanel?.classList.contains('hidden')) {
      renderCalendar();
      openDatePicker();
      return;
    }

    closeDatePicker();
  });

  datePickerPanel?.addEventListener('click', event => {
    const navButton = event.target.closest('[data-calendar-nav]');
    if (navButton) {
      calendarDate.setMonth(calendarDate.getMonth() + (navButton.dataset.calendarNav === 'next' ? 1 : -1));
      renderCalendar();
      return;
    }

    const dateButton = event.target.closest('[data-calendar-date]');
    if (!dateButton || !closeDateDisplay || !closeDateInput) {
      return;
    }

    closeDateInput.value = dateButton.dataset.calendarDate || '';
    closeDateDisplay.value = toDisplayDate(closeDateInput.value);
    renderCalendar();
    closeDatePicker();
  });

  document.addEventListener('click', event => {
    if (!datePicker || datePicker.contains(event.target)) {
      return;
    }

    closeDatePicker();
  });

  removeHandlers();
  refreshRequirementIndexes();
  tagWeight.value = resolveDefaultRequirementWeight(tagType.value);
  syncCloseDate();
});
