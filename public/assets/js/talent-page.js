document.addEventListener('DOMContentLoaded', () => {
  const reuseModal = document.getElementById('talentReuseModal');
  const closeButtons = document.querySelectorAll('[data-action="close-talent-reuse-modal"]');
  const reuseTitle = document.getElementById('talentReuseTitle');
  const reuseVacancyInput = document.getElementById('talentReuseVacancy');
  const reuseNoteInput = document.getElementById('talentReuseNote');
  const reuseSaveButton = document.getElementById('talentReuseSave');
  const notice = document.getElementById('talentPoolNotice');
  const searchInput = document.getElementById('talentSearch');
  const categoryFilter = document.getElementById('talentCategory');
  const cards = Array.from(document.querySelectorAll('[data-talent-card]'));
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
  let selectedTalentPayload = null;

  const flashSuccess = sessionStorage.getItem('talent_pool_success');
  if (notice && flashSuccess) {
    notice.textContent = flashSuccess;
    notice.style.display = 'block';
    sessionStorage.removeItem('talent_pool_success');
  }

  document.addEventListener('click', event => {
    const button = event.target.closest('[data-action="open-reuse-modal"]');
    if (!button) {
      return;
    }

    const card = button.closest('[data-talent-card]');
    if (!card) {
      return;
    }

    selectedTalentPayload = {
      talentPoolId: card.dataset.talentPoolId,
      candidateId: card.dataset.candidateId,
      candidateName: card.dataset.candidateName || 'Namizəd',
      sourceVacancyId: card.dataset.sourceVacancyId || '',
      addUrl: card.dataset.addUrl || ''
    };

    if (reuseTitle) {
      reuseTitle.textContent = `${selectedTalentPayload.candidateName} / Vakansiyaya əlavə et`;
    }

    if (reuseVacancyInput) {
      reuseVacancyInput.value = '';
    }

    if (reuseNoteInput) {
      reuseNoteInput.value = '';
    }

    reuseModal?.classList.add('open');
  });

  closeButtons.forEach(button => button.addEventListener('click', () => reuseModal?.classList.remove('open')));
  reuseModal?.addEventListener('click', event => {
    if (event.target === reuseModal) {
      reuseModal.classList.remove('open');
    }
  });

  reuseSaveButton?.addEventListener('click', async () => {
    if (!selectedTalentPayload) {
      return;
    }

    if (!selectedTalentPayload.addUrl) {
      window.alert('Add to vacancy route is not ready');
      return;
    }

    if (!reuseVacancyInput?.value) {
      window.alert('Vakansiya seçin');
      return;
    }

    reuseSaveButton.disabled = true;
    reuseSaveButton.textContent = 'Göndərilir...';

    try {
      const response = await fetch(selectedTalentPayload.addUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken || '',
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          talent_pool_id: selectedTalentPayload.talentPoolId,
          candidate_id: selectedTalentPayload.candidateId,
          source_vacancy_id: selectedTalentPayload.sourceVacancyId,
          vacancy_id: reuseVacancyInput.value,
          note: reuseNoteInput?.value.trim() || ''
        })
      });

      const data = await response.json();

      if (!response.ok || !data.success) {
        throw new Error(data.message || 'Vakansiyaya əlavə etmə uğursuz oldu');
      }

      if (data.redirect_url) {
        sessionStorage.setItem('talent_pool_success', data.message || 'Namizəd vakansiyaya əlavə olundu');
        window.location.href = data.redirect_url;
        return;
      }

      sessionStorage.setItem('talent_pool_success', data.message || 'Namizəd vakansiyaya əlavə olundu');
      window.location.reload();
    } catch (error) {
      window.alert(error.message || 'Vakansiyaya əlavə etmə uğursuz oldu');
      reuseSaveButton.disabled = false;
      reuseSaveButton.textContent = 'Vakansiyaya əlavə et';
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
