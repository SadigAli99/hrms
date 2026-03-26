document.addEventListener('DOMContentLoaded', () => {
  const openUploadButtons = document.querySelectorAll('[data-action="open-upload-modal"]');
  const closeUploadButtons = document.querySelectorAll('[data-action="close-upload-modal"]');
  const closeAnalyzeButtons = document.querySelectorAll('[data-action="close-analyze-modal"]');
  const closeProfileButtons = document.querySelectorAll('[data-action="close-profile-modal"]');
  const closeInterviewButtons = document.querySelectorAll('[data-action="close-interview-modal"]');
  const closeTalentButtons = document.querySelectorAll('[data-action="close-talent-modal"]');
  const closeRejectButtons = document.querySelectorAll('[data-action="close-reject-modal"]');
  const closeOfferButtons = document.querySelectorAll('[data-action="close-offer-modal"]');

  const uploadModal = document.getElementById('candidateUploadModal');
  const analyzeModal = document.getElementById('candidateAnalyzeModal');
  const profileModal = document.getElementById('candidateProfileModal');
  const interviewModal = document.getElementById('candidateInterviewModal');
  const talentModal = document.getElementById('candidateTalentModal');
  const rejectModal = document.getElementById('candidateRejectModal');
  const offerModal = document.getElementById('candidateOfferModal');

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

  const interviewAction = document.getElementById('candidateInterviewAction');
  const talentAction = document.getElementById('candidateTalentAction');
  const rejectAction = document.getElementById('candidateRejectAction');
  const offerAction = document.getElementById('candidateOfferAction');

  let queue = [];
  let uploadedFiles = [];

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
    name: file.name,
    size: file.size || 0,
    type: file.name.includes('.') ? file.name.split('.').pop().toUpperCase() : 'FILE'
  }));

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

    if (!uploadedFiles.length) {
      analyzeList.innerHTML = '<div class="empty-state">No uploaded files available for analysis.</div>';
      if (confirmAnalysisButton) {
        confirmAnalysisButton.disabled = true;
      }
      return;
    }

    analyzeList.innerHTML = uploadedFiles.map((file, index) => `
      <label class="analysis-option">
        <input type="checkbox" data-analyze-file value="${index}" checked>
        <div>
          <strong>${file.name}</strong>
          <span>${file.type} / ${formatFileSize(file.size)}</span>
        </div>
      </label>
    `).join('');

    if (confirmAnalysisButton) {
      confirmAnalysisButton.disabled = false;
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
    renderAnalyzeList();
    analyzeModal?.classList.add('open');
  });
  interviewAction?.addEventListener('click', () => interviewModal?.classList.add('open'));
  talentAction?.addEventListener('click', () => talentModal?.classList.add('open'));
  rejectAction?.addEventListener('click', () => rejectModal?.classList.add('open'));
  offerAction?.addEventListener('click', () => offerModal?.classList.add('open'));

  document.querySelectorAll('[data-open-profile]').forEach(button => {
    button.addEventListener('click', () => profileModal?.classList.add('open'));
  });

  bindClose(closeUploadButtons, uploadModal);
  bindClose(closeAnalyzeButtons, analyzeModal);
  bindClose(closeProfileButtons, profileModal);
  bindClose(closeInterviewButtons, interviewModal);
  bindClose(closeTalentButtons, talentModal);
  bindClose(closeRejectButtons, rejectModal);
  bindClose(closeOfferButtons, offerModal);

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

  confirmUploadButton?.addEventListener('click', () => {
    uploadedFiles = [...queue, ...uploadedFiles];
    queue = [];
    renderQueue();
    renderUploadedFiles();
    renderAnalyzeList();
    uploadModal?.classList.remove('open');
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

  confirmAnalysisButton?.addEventListener('click', () => {
    if (!analyzeProgress) {
      analyzeModal?.classList.remove('open');
      return;
    }

    analyzeProgress.hidden = false;
    confirmAnalysisButton.disabled = true;

    window.setTimeout(() => {
      analyzeProgress.hidden = true;
      confirmAnalysisButton.disabled = false;
      analyzeModal?.classList.remove('open');
    }, 1200);
  });

  renderQueue();
  renderUploadedFiles();
  renderAnalyzeList();
});
