document.addEventListener('DOMContentLoaded', () => {
  const openUploadButtons = document.querySelectorAll('[data-action="open-upload-modal"]');
  const closeUploadButtons = document.querySelectorAll('[data-action="close-upload-modal"]');
  const closeAnalyzeButtons = document.querySelectorAll('[data-action="close-analyze-modal"]');
  const closeProfileButtons = document.querySelectorAll('[data-action="close-profile-modal"]');
  const closeDeleteCvButtons = document.querySelectorAll('[data-action="close-delete-cv-modal"]');
  const closeInterviewButtons = document.querySelectorAll('[data-action="close-interview-modal"]');
  const closeTalentButtons = document.querySelectorAll('[data-action="close-talent-modal"]');
  const closeRejectButtons = document.querySelectorAll('[data-action="close-reject-modal"]');
  const closeOfferButtons = document.querySelectorAll('[data-action="close-offer-modal"]');
  const closeRetryParseButtons = document.querySelectorAll('[data-action="close-retry-parse-modal"]');

  const uploadModal = document.getElementById('candidateUploadModal');
  const analyzeModal = document.getElementById('candidateAnalyzeModal');
  const profileModal = document.getElementById('candidateProfileModal');
  const deleteCvModal = document.getElementById('candidateDeleteCvModal');
  const interviewModal = document.getElementById('candidateInterviewModal');
  const talentModal = document.getElementById('candidateTalentModal');
  const rejectModal = document.getElementById('candidateRejectModal');
  const offerModal = document.getElementById('candidateOfferModal');
  const retryParseModal = document.getElementById('candidateRetryParseModal');

  const fileInput = document.getElementById('candidateFileInput');
  const dropzone = document.getElementById('candidateDropzone');
  const uploadQueue = document.getElementById('candidateUploadQueue');
  const uploadedCvList = document.getElementById('uploadedCvList');
  const analyzeList = document.getElementById('candidateAnalyzeList');
  const clearQueueButton = document.getElementById('clearCandidateQueue');
  const confirmUploadButton = document.getElementById('confirmCandidateUpload');
  const analyzeButton = document.getElementById('runCandidateAnalysis');
  const confirmAnalysisButton = document.getElementById('confirmCandidateAnalysis');
  const selectAllAnalyzeFiles = document.getElementById('selectAllAnalyzeFiles');
  const loadCandidateSample = document.getElementById('loadCandidateSample');
  const analyzeProgress = document.getElementById('candidateAnalyzeProgress');
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

  const interviewAction = document.getElementById('candidateInterviewAction');
  const talentAction = document.getElementById('candidateTalentAction');
  const rejectAction = document.getElementById('candidateRejectAction');
  const offerAction = document.getElementById('candidateOfferAction');
  const candidateProfileName = document.getElementById('candidateProfileName');
  const candidateProfileMeta = document.getElementById('candidateProfileMeta');
  const candidateProfileScore = document.getElementById('candidateProfileScore');
  const candidateProfileSummary = document.getElementById('candidateProfileSummary');
  const candidateProfileStatus = document.getElementById('candidateProfileStatus');
  const candidateProfileExperience = document.getElementById('candidateProfileExperience');
  const candidateProfileSkillsScore = document.getElementById('candidateProfileSkillsScore');
  const candidateProfileExperienceScore = document.getElementById('candidateProfileExperienceScore');
  const candidateProfileEducationScore = document.getElementById('candidateProfileEducationScore');
  const candidateProfileLanguageScore = document.getElementById('candidateProfileLanguageScore');
  const candidateProfileSkills = document.getElementById('candidateProfileSkills');
  const candidateProfileGaps = document.getElementById('candidateProfileGaps');
  const candidateProfileCriticalMissing = document.getElementById('candidateProfileCriticalMissing');
  const candidateProfileRiskFlags = document.getElementById('candidateProfileRiskFlags');
  const candidateProfileRequirements = document.getElementById('candidateProfileRequirements');
  const candidateProfileHistory = document.getElementById('candidateProfileHistory');
  const candidateProfileScoreStroke = document.getElementById('candidateProfileScoreStroke');
  const candidateProfileFitMeta = document.getElementById('candidateProfileFitMeta');
  const candidateProfileScoreReasoning = document.getElementById('candidateProfileScoreReasoning');
  const candidateProfileScoreAdjustments = document.getElementById('candidateProfileScoreAdjustments');
  const candidateDeleteCvForm = document.getElementById('candidateDeleteCvForm');
  const candidateDeleteCvName = document.getElementById('candidateDeleteCvName');
  const candidateDeleteCvConfirm = document.getElementById('candidateDeleteCvConfirm');
  const candidateRetryParseName = document.getElementById('candidateRetryParseName');
  const candidateRetryParseConfirm = document.getElementById('candidateRetryParseConfirm');

  let queue = [];
  let uploadedFiles = [];
  let pendingDeleteCv = null;
  let pendingRetryParse = null;

  const escapeHtml = value => String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');

  const renderProfileTags = (container, items, emptyText) => {
    if (!container) {
      return;
    }

    if (!Array.isArray(items) || !items.length) {
      container.innerHTML = `<span class="vacancy-chip">${escapeHtml(emptyText)}</span>`;
      return;
    }

    container.innerHTML = items
      .map(item => `<span class="vacancy-chip">${escapeHtml(item)}</span>`)
      .join('');
  };

  const renderProfileHistory = items => {
    if (!candidateProfileHistory) {
      return;
    }

    if (!Array.isArray(items) || !items.length) {
      candidateProfileHistory.innerHTML = '<div class="empty-state">History yoxdur.</div>';
      return;
    }

    candidateProfileHistory.innerHTML = items
      .map(item => `
        <div class="upload-item">
          <div class="upload-item-content">
            <strong>${escapeHtml(item)}</strong>
          </div>
        </div>
      `)
      .join('');
  };

  const renderRequirementComparison = items => {
    if (!candidateProfileRequirements) {
      return;
    }

    if (!Array.isArray(items) || !items.length) {
      candidateProfileRequirements.innerHTML = '<div class="empty-state">Vakansiya tələbi yoxdur.</div>';
      return;
    }

    candidateProfileRequirements.innerHTML = items
      .map(item => `
        <div class="profile-requirement-item is-${escapeHtml(item.status || 'review')}">
          <div class="profile-requirement-copy">
            <strong>${escapeHtml(item.label || 'Requirement')}</strong>
          </div>
          <span class="profile-requirement-badge is-${escapeHtml(item.status || 'review')}">${escapeHtml(item.statusLabel || 'Yoxlanmalı')}</span>
        </div>
      `)
      .join('');
  };

  const renderEnhancedRequirementComparison = items => {
    if (!candidateProfileRequirements) {
      return;
    }

    if (!Array.isArray(items) || !items.length) {
      candidateProfileRequirements.innerHTML = '<div class="empty-state">Vakansiya tələbi yoxdur.</div>';
      return;
    }

    candidateProfileRequirements.innerHTML = items
      .map(item => {
        const meta = [];

        if (item.confidence !== null && item.confidence !== undefined) {
          meta.push(`<span>${escapeHtml(item.confidence)}% confidence</span>`);
        }

        if (item.weightApplied) {
          meta.push(`<span>${escapeHtml(item.weightApplied)}</span>`);
        }

        return `
          <div class="profile-requirement-item is-${escapeHtml(item.status || 'review')}">
            <div class="profile-requirement-copy">
              <strong>${escapeHtml(item.label || 'Requirement')}</strong>
              ${meta.length ? `<div class="profile-requirement-meta">${meta.join('')}</div>` : ''}
              ${item.notes ? `<div class="profile-requirement-note">${escapeHtml(item.notes)}</div>` : ''}
            </div>
            <span class="profile-requirement-badge is-${escapeHtml(item.status || 'review')}">${escapeHtml(item.statusLabel || 'Yoxlanmalı')}</span>
          </div>
        `;
      })
      .join('');
  };

  const renderSignalList = (container, items, emptyText, variant = 'default') => {
    if (!container) {
      return;
    }

    if (!Array.isArray(items) || !items.length) {
      container.innerHTML = `<div class="empty-state">${escapeHtml(emptyText)}</div>`;
      return;
    }

    container.innerHTML = items
      .map(item => `<span class="profile-signal-chip is-${escapeHtml(variant)}">${escapeHtml(item)}</span>`)
      .join('');
  };

  const renderScoreAdjustments = items => {
    if (!candidateProfileScoreAdjustments) {
      return;
    }

    if (!Array.isArray(items) || !items.length) {
      candidateProfileScoreAdjustments.innerHTML = '<div class="empty-state">Adjustment yoxdur.</div>';
      return;
    }

    candidateProfileScoreAdjustments.innerHTML = items
      .map(item => {
        const impact = Number(item.impact ?? 0);
        const impactLabel = impact > 0 ? `+${impact}` : `${impact}`;
        const impactClass = impact > 0 ? 'positive' : (impact < 0 ? 'negative' : 'neutral');

        return `
          <div class="profile-adjustment-item">
            <div class="profile-adjustment-copy">
              <strong>${escapeHtml(item.factor || 'factor')}</strong>
              <div>${escapeHtml(item.reason || 'No reason provided')}</div>
            </div>
            <span class="profile-adjustment-impact is-${impactClass}">${escapeHtml(impactLabel)}</span>
          </div>
        `;
      })
      .join('');
  };

  const fillProfileModal = payload => {
    if (!payload) {
      return;
    }

    if (candidateProfileName) {
      candidateProfileName.textContent = payload.name || 'Candidate Profile';
    }

    if (candidateProfileMeta) {
      candidateProfileMeta.textContent = payload.meta || 'Elaqe melumati yoxdur';
    }

    if (candidateProfileScore) {
      candidateProfileScore.textContent = payload.score || '0%';
    }

    if (candidateProfileScoreStroke) {
      const rawScore = Number(payload.scoreValue ?? 0);
      const percent = Math.max(0, Math.min(100, rawScore));
      const radius = 30;
      const circumference = 2 * Math.PI * radius;
      const offset = circumference - ((percent / 100) * circumference);
      let strokeColor = '#3178f6';

      if (percent >= 85) {
        strokeColor = '#3178f6';
      } else if (percent >= 70) {
        strokeColor = '#8b5cf6';
      } else if (percent >= 50) {
        strokeColor = '#10b981';
      } else {
        strokeColor = '#f59e0b';
      }

      candidateProfileScoreStroke.setAttribute('stroke-dasharray', circumference.toFixed(1));
      candidateProfileScoreStroke.setAttribute('stroke-dashoffset', offset.toFixed(1));
      candidateProfileScoreStroke.setAttribute('stroke', strokeColor);
    }

    if (candidateProfileSummary) {
      candidateProfileSummary.textContent = payload.summary || 'AI summary yoxdur';
    }

    if (candidateProfileStatus) {
      candidateProfileStatus.innerHTML = `<span class="badge ${escapeHtml(payload.statusClass || 'badge-cyan')}">${escapeHtml(payload.statusLabel || 'Unknown')}</span>`;
    }

    if (candidateProfileExperience) {
      const experience = payload.experience || 'Tecrube yoxdur';
      const currentTitle = payload.currentTitle || 'Title yoxdur';
      candidateProfileExperience.textContent = `${experience} · ${currentTitle}`;
    }

    if (candidateProfileFitMeta) {
      const fitParts = [];

      if (payload.seniorityFit) {
        fitParts.push(`Seniority: ${payload.seniorityFit}`);
      }
      if (payload.salaryFit) {
        fitParts.push(`Salary: ${payload.salaryFit}`);
      }
      if (payload.expectedSalary) {
        fitParts.push(`Expected: ${payload.expectedSalary}`);
      }
      if (payload.vacancySalary) {
        fitParts.push(`Vacancy range: ${payload.vacancySalary}`);
      }

      candidateProfileFitMeta.textContent = fitParts.length ? fitParts.join(' · ') : 'Fit details yoxdur';
    }

    if (candidateProfileScoreReasoning) {
      candidateProfileScoreReasoning.textContent = payload.scoreReasoning || 'No score reasoning available.';
    }

    if (candidateProfileSkillsScore) {
      candidateProfileSkillsScore.textContent = payload.skillsScore || '0%';
    }

    if (candidateProfileExperienceScore) {
      candidateProfileExperienceScore.textContent = payload.experienceScore || '0%';
    }

    if (candidateProfileEducationScore) {
      candidateProfileEducationScore.textContent = payload.educationScore || '0%';
    }

    if (candidateProfileLanguageScore) {
      candidateProfileLanguageScore.textContent = payload.languageScore || '0%';
    }

    renderProfileTags(candidateProfileSkills, payload.skills, 'Uygun skill yoxdur');
    renderProfileTags(candidateProfileGaps, payload.gaps, 'Bosluq yoxdur');
    renderSignalList(candidateProfileCriticalMissing, payload.criticalMissing, 'Critical gap yoxdur', 'critical');
    renderSignalList(candidateProfileRiskFlags, payload.riskFlags, 'Risk yoxdur', 'risk');
    renderScoreAdjustments(payload.scoreAdjustments);
    renderEnhancedRequirementComparison(payload.requirements);
    renderProfileHistory(payload.history);
  };

  const bindClose = (buttons, modal) => {
    buttons.forEach(button => button.addEventListener('click', () => modal?.classList.remove('open')));
    modal?.addEventListener('click', event => {
      if (event.target === modal) {
        modal.classList.remove('open');
      }
    });
  };

  const formatFileSize = size => {
    if (!size) {
      return '0 KB';
    }
    if (size >= 1024 * 1024) {
      return `${(size / (1024 * 1024)).toFixed(1)} MB`;
    }
    return `${Math.max(1, Math.round(size / 1024))} KB`;
  };

  const normalizeFiles = fileList => Array.from(fileList).map(file => ({
    file,
    name: file.name,
    size: file.size || 0,
    type: file.name.includes('.') ? file.name.split('.').pop().toUpperCase() : 'FILE'
  }));

  const setCvItemAnalyzingState = cvFileId => {
    const item = document.querySelector(`[data-cv-item][data-cv-file-id="${cvFileId}"]`);

    if (!item) {
      return;
    }

    item.classList.add('is-analyzing');

    const statusWrap = item.querySelector('[data-cv-status-wrap]');
    if (statusWrap) {
      statusWrap.innerHTML = `
        <span class="status-pill processing">
          <span class="analysis-inline-spinner" aria-hidden="true"></span>
          AI analyzing
        </span>
      `;
    }

    item.querySelectorAll('button').forEach(button => {
      button.disabled = true;
    });
  };

  const setMultipleCvItemsAnalyzingState = ids => {
    ids.forEach(id => setCvItemAnalyzingState(id));
  };

  const renderQueue = () => {
    if (!uploadQueue) {
      return;
    }

    if (!queue.length) {
      uploadQueue.innerHTML = '<div class="empty-state">No files selected yet.</div>';
      if (clearQueueButton) {
        clearQueueButton.disabled = true;
      }
      if (confirmUploadButton) {
        confirmUploadButton.disabled = true;
      }
      return;
    }

    uploadQueue.innerHTML = queue.map((file, index) => `
      <div class="upload-item">
        <div>
          <strong>${file.name}</strong>
          <span>${file.type} / ${formatFileSize(file.size)}</span>
        </div>
        <button type="button" data-remove-queued-file="${index}">Remove</button>
      </div>
    `).join('');

    uploadQueue.querySelectorAll('[data-remove-queued-file]').forEach(button => {
      button.addEventListener('click', () => {
        queue.splice(Number(button.dataset.removeQueuedFile), 1);
        renderQueue();
      });
    });

    if (clearQueueButton) {
      clearQueueButton.disabled = false;
    }
    if (confirmUploadButton) {
      confirmUploadButton.disabled = false;
    }
  };

  const renderUploadedFiles = () => {
    if (!uploadedCvList) {
      return;
    }

    if (!uploadedFiles.length) {
      if (uploadedCvList.dataset.serverRendered === 'true') {
        return;
      }
      uploadedCvList.innerHTML = '<div class="empty-state">No uploaded CV files yet.</div>';
      return;
    }

    uploadedCvList.innerHTML = uploadedFiles.map((file, index) => `
      <div class="upload-item is-uploaded">
        <div>
          <strong>${file.name}</strong>
          <span>${file.type} / ${formatFileSize(file.size)}</span>
          <div class="mt-2"><span class="status-pill parsed">uploaded</span></div>
        </div>
        <div class="flex gap-3 items-center">
          <button type="button" data-remove-uploaded-file="${index}">Delete</button>
        </div>
      </div>
    `).join('');

    uploadedCvList.querySelectorAll('[data-remove-uploaded-file]').forEach(button => {
      button.addEventListener('click', () => {
        uploadedFiles.splice(Number(button.dataset.removeUploadedFile), 1);
        renderUploadedFiles();
        renderAnalyzeList();
      });
    });
  };

  const renderAnalyzeList = () => {
    if (!analyzeList) {
      return;
    }
    const hasAnalyzeItems = analyzeList.querySelectorAll('[data-analyze-file]').length > 0;
    if (confirmAnalysisButton) {
      confirmAnalysisButton.disabled = !hasAnalyzeItems;
    }
  };

  const appendToQueue = files => {
    normalizeFiles(files).forEach(file => {
      const duplicate = queue.some(item => item.name === file.name && item.size === file.size);
      if (!duplicate) {
        queue.push(file);
      }
    });
    renderQueue();
  };

  openUploadButtons.forEach(button => button.addEventListener('click', () => uploadModal?.classList.add('open')));
  analyzeButton?.addEventListener('click', () => {
    analyzeModal?.classList.add('open');
  });
  interviewAction?.addEventListener('click', () => interviewModal?.classList.add('open'));
  talentAction?.addEventListener('click', () => talentModal?.classList.add('open'));
  rejectAction?.addEventListener('click', () => rejectModal?.classList.add('open'));
  offerAction?.addEventListener('click', () => offerModal?.classList.add('open'));

  document.querySelectorAll('[data-open-profile]').forEach(button => {
    button.addEventListener('click', () => {
      const payload = button.dataset.profile ? JSON.parse(button.dataset.profile) : null;
      fillProfileModal(payload);
      profileModal?.classList.add('open');
    });
  });

  bindClose(closeUploadButtons, uploadModal);
  bindClose(closeAnalyzeButtons, analyzeModal);
  bindClose(closeProfileButtons, profileModal);
  bindClose(closeDeleteCvButtons, deleteCvModal);
  bindClose(closeInterviewButtons, interviewModal);
  bindClose(closeTalentButtons, talentModal);
  bindClose(closeRejectButtons, rejectModal);
  bindClose(closeOfferButtons, offerModal);
  bindClose(closeRetryParseButtons, retryParseModal);

  document.querySelectorAll('[data-open-delete-cv-modal]').forEach(button => {
    button.addEventListener('click', () => {
      pendingDeleteCv = {
        id: button.dataset.cvFileId,
        name: button.dataset.cvFileName || 'CV file',
        url: button.dataset.deleteUrl
      };

      if (candidateDeleteCvName) {
        candidateDeleteCvName.textContent = pendingDeleteCv.name;
      }

      if (candidateDeleteCvForm) {
        candidateDeleteCvForm.setAttribute('action', pendingDeleteCv.url || '#');
      }

      deleteCvModal?.classList.add('open');
    });
  });

  candidateDeleteCvForm?.addEventListener('submit', () => {
    if (candidateDeleteCvConfirm) {
      candidateDeleteCvConfirm.disabled = true;
      candidateDeleteCvConfirm.textContent = 'Deleting...';
    }
  });

  document.querySelectorAll('[data-open-retry-parse-modal]').forEach(button => {
    button.addEventListener('click', () => {
      pendingRetryParse = {
        id: button.dataset.cvFileId,
        name: button.dataset.cvFileName || 'CV file',
        url: button.dataset.retryUrl
      };

      if (candidateRetryParseName) {
        candidateRetryParseName.textContent = pendingRetryParse.name;
      }

      retryParseModal?.classList.add('open');
    });
  });

  fileInput?.addEventListener('change', event => {
    appendToQueue(event.target.files);
    fileInput.value = '';
  });

  dropzone?.addEventListener('click', () => fileInput?.click());
  dropzone?.addEventListener('dragover', event => {
    event.preventDefault();
    dropzone.classList.add('is-dragging');
  });
  dropzone?.addEventListener('dragleave', () => {
    dropzone.classList.remove('is-dragging');
  });
  dropzone?.addEventListener('drop', event => {
    event.preventDefault();
    dropzone.classList.remove('is-dragging');
    if (event.dataTransfer.files.length) {
      appendToQueue(event.dataTransfer.files);
    }
  });

  clearQueueButton?.addEventListener('click', () => {
    queue = [];
    renderQueue();
  });

  confirmUploadButton?.addEventListener('click', async () => {
    const uploadUrl = confirmUploadButton.dataset.uploadUrl;
    const validFiles = queue.filter(item => item.file instanceof File);

    if (!uploadUrl || !validFiles.length) {
      return;
    }

    const formData = new FormData();
    validFiles.forEach(item => {
      formData.append('files[]', item.file);
    });

    confirmUploadButton.disabled = true;

    try {
      const response = await fetch(uploadUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrfToken || '',
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        body: formData
      });

      const data = await response.json();

      if (!response.ok || !data.success) {
        throw new Error(data.message || 'Upload failed');
      }

      window.location.reload();
    } catch (error) {
      window.alert(error.message || 'Upload failed');
      confirmUploadButton.disabled = false;
    }
  });

  loadCandidateSample?.addEventListener('click', () => {
    queue = [
      { name: 'kamran-nasirov-cv.pdf', size: 482315, type: 'PDF' },
      { name: 'aynur-quliyeva-cv.docx', size: 221300, type: 'DOCX' },
      { name: 'backend-batch-march.zip', size: 3200300, type: 'ZIP' }
    ];
    renderQueue();
    uploadModal?.classList.add('open');
  });

  selectAllAnalyzeFiles?.addEventListener('click', () => {
    analyzeList?.querySelectorAll('[data-analyze-file]').forEach(input => {
      input.checked = true;
    });
  });

  confirmAnalysisButton?.addEventListener('click', async () => {
    const bulkAnalyzeUrl = confirmAnalysisButton.dataset.bulkAnalyzeUrl;
    const selectedIds = Array.from(analyzeList?.querySelectorAll('[data-analyze-file]:checked') || [])
      .map(input => input.value)
      .filter(Boolean);

    if (!bulkAnalyzeUrl) {
      window.alert('Bulk analyze route is not ready');
      return;
    }

    if (!selectedIds.length) {
      window.alert('Analyze etmek ucun en azi bir CV secin');
      return;
    }

    if (!window.confirm('Secilmis CV fayllarini AI analizine gondermek istediyinize eminsiniz?')) {
      return;
    }

    analyzeProgress.hidden = false;
    confirmAnalysisButton.disabled = true;
    setMultipleCvItemsAnalyzingState(selectedIds);

    try {
      const response = await fetch(bulkAnalyzeUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken || '',
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          cv_file_ids: selectedIds
        })
      });

      const data = await response.json();

      if (!response.ok || !data.success) {
        throw new Error(data.message || 'Bulk analyze failed');
      }

      window.location.reload();
    } catch (error) {
      window.alert(error.message || 'Bulk analyze failed');
      analyzeProgress.hidden = true;
      confirmAnalysisButton.disabled = false;
    }
  });

  document.querySelectorAll('[data-analyze-cv-trigger]').forEach(button => {
    button.addEventListener('click', async () => {
      const analyzeUrl = button.dataset.analyzeUrl;
      const cvFileId = button.dataset.cvFileId;

      if (!analyzeUrl || !cvFileId) {
        return;
      }

      setCvItemAnalyzingState(cvFileId);
      button.disabled = true;

      try {
        const response = await fetch(analyzeUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken || '',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            cv_file_id: cvFileId
          })
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
          throw new Error(data.message || 'Analyze failed');
        }

        window.location.reload();
      } catch (error) {
        window.alert(error.message || 'Analyze failed');
        button.disabled = false;
      }
    });
  });

  candidateRetryParseConfirm?.addEventListener('click', async () => {
      const retryUrl = pendingRetryParse?.url;
      const cvFileId = pendingRetryParse?.id;

      if (!retryUrl || !cvFileId) {
        window.alert('Retry parse route is not ready');
        return;
      }

      setCvItemAnalyzingState(cvFileId);
      candidateRetryParseConfirm.disabled = true;
      candidateRetryParseConfirm.textContent = 'Retrying...';
      retryParseModal?.classList.remove('open');

      try {
        const response = await fetch(retryUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken || '',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            cv_file_id: cvFileId
          })
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
          throw new Error(data.message || 'Retry parse failed');
        }

        window.location.reload();
      } catch (error) {
        window.alert(error.message || 'Retry parse failed');
        candidateRetryParseConfirm.disabled = false;
        candidateRetryParseConfirm.textContent = 'Retry parse';
      }
  });

  renderQueue();
  renderUploadedFiles();
  renderAnalyzeList();
});
