const html = document.documentElement;

document.addEventListener('DOMContentLoaded', () => {
  if (localStorage.getItem('theme') === 'light') {
    html.classList.add('light');
  }

  if (document.body.dataset.page === 'analytics') {
    setTimeout(initCharts, 40);
  }

  if (document.body.dataset.page === 'vacancies') {
    initVacanciesPage();
  }

  if (document.body.dataset.page === 'vacancy-create') {
    initVacancyBuilderPage();
  }

  if (document.body.dataset.page === 'candidates') {
    initCandidatesPage();
  }

  if (document.body.dataset.page === 'interviews') {
    initInterviewsPage();
  }

  if (document.body.dataset.page === 'talent') {
    initTalentPage();
  }
});

function toggleTheme() {
  const isLight = html.classList.toggle('light');
  localStorage.setItem('theme', isLight ? 'light' : 'dark');

  if (window.Chart && document.body.dataset.page === 'analytics') {
    Object.values(Chart.instances).forEach(chart => chart.destroy());
    ['hireChart', 'deptChart', 'leaveChart', 'perfChart'].forEach(id => {
      const element = document.getElementById(id);
      if (element) {
        delete element._chartInit;
      }
    });
    setTimeout(initCharts, 80);
  }
}

function initCharts() {
  if (!window.Chart) {
    return;
  }

  const isLight = document.documentElement.classList.contains('light');
  const gridColor = isLight ? 'rgba(0,0,0,0.06)' : 'rgba(255,255,255,0.04)';
  const tickColor = isLight ? '#94a3b8' : '#64748b';
  const legendColor = isLight ? '#64748b' : '#94a3b8';
  Chart.defaults.font.family = 'DM Sans';

  const hireCtx = document.getElementById('hireChart');
  if (hireCtx && !hireCtx._chartInit) {
    hireCtx._chartInit = true;
    new Chart(hireCtx, {
      type: 'bar',
      data: {
        labels: ['Okt', 'Noy', 'Dek', 'Yan', 'Fev', 'Mar'],
        datasets: [
          { label: 'Muraciet', data: [180, 210, 165, 240, 195, 247], backgroundColor: 'rgba(49,120,246,0.6)', borderRadius: 4 },
          { label: 'Qebul Edildi', data: [12, 15, 10, 18, 14, 18], backgroundColor: 'rgba(16,185,129,0.7)', borderRadius: 4 }
        ]
      },
      options: {
        responsive: true,
        plugins: { legend: { labels: { color: legendColor, font: { size: 11 } } } },
        scales: {
          x: { grid: { color: gridColor }, ticks: { color: tickColor } },
          y: { grid: { color: gridColor }, ticks: { color: tickColor } }
        }
      }
    });
  }

  const deptCtx = document.getElementById('deptChart');
  if (deptCtx && !deptCtx._chartInit) {
    deptCtx._chartInit = true;
    new Chart(deptCtx, {
      type: 'doughnut',
      data: {
        labels: ['IT', 'Maliyye', 'HR', 'Marketinq', 'Satis', 'Diger'],
        datasets: [{ data: [6, 2, 1, 2, 2, 1], backgroundColor: ['#3178f6', '#8b5cf6', '#22d3ee', '#10b981', '#f59e0b', '#64748b'], borderWidth: 0 }]
      },
      options: {
        responsive: true,
        cutout: '68%',
        plugins: { legend: { position: 'right', labels: { color: legendColor, font: { size: 11 }, padding: 12 } } }
      }
    });
  }

  const leaveCtx = document.getElementById('leaveChart');
  if (leaveCtx && !leaveCtx._chartInit) {
    leaveCtx._chartInit = true;
    new Chart(leaveCtx, {
      type: 'line',
      data: {
        labels: ['Yan', 'Fev', 'Mar', 'Apr', 'May', 'Iyn'],
        datasets: [
          { label: 'Illik Mezuniyyet', data: [45, 62, 58, 80, 120, 145], borderColor: '#3178f6', backgroundColor: 'rgba(49,120,246,0.1)', fill: true, tension: 0.4, pointRadius: 4 },
          { label: 'Tibbi Icaze', data: [12, 8, 15, 11, 9, 14], borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.08)', fill: true, tension: 0.4, pointRadius: 4 }
        ]
      },
      options: {
        responsive: true,
        plugins: { legend: { labels: { color: legendColor, font: { size: 11 } } } },
        scales: {
          x: { grid: { color: gridColor }, ticks: { color: tickColor } },
          y: { grid: { color: gridColor }, ticks: { color: tickColor } }
        }
      }
    });
  }

  const perfCtx = document.getElementById('perfChart');
  if (perfCtx && !perfCtx._chartInit) {
    perfCtx._chartInit = true;
    new Chart(perfCtx, {
      type: 'bar',
      data: {
        labels: ['Ustun (4.5+)', 'Yaxsi (3.5-4.5)', 'Orta (2.5-3.5)', 'Zeif (<2.5)'],
        datasets: [{ data: [38, 74, 45, 12], backgroundColor: ['#10b981', '#3178f6', '#f59e0b', '#f43f5e'], borderRadius: 4 }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { color: gridColor }, ticks: { color: tickColor, font: { size: 11 } } },
          y: { grid: { color: gridColor }, ticks: { color: tickColor } }
        }
      }
    });
  }
}

const VACANCY_STORAGE_KEY = 'hrms-vacancy-mock-state-v1';
const CV_STORAGE_KEY = 'hrms-cv-intake-mock-v1';
const VACANCY_STATUS_META = {
  draft: { label: 'Draft', badge: 'badge-yellow' },
  published: { label: 'Published', badge: 'badge-green' },
  paused: { label: 'Paused', badge: 'badge-cyan' },
  closed: { label: 'Closed', badge: 'badge-red' }
};

function getSeedVacancies() {
  return [
    {
      id: 'vac-001',
      title: 'Backend Developer',
      department: 'IT',
      employmentType: 'Full-time',
      workMode: 'Hybrid',
      seniority: 'Middle',
      minSalary: '2500',
      maxSalary: '4000',
      currency: 'AZN',
      location: 'Baku',
      closeDate: '2026-03-28',
      status: 'published',
      applicants: 47,
      interviews: 3,
      createdAt: '2026-03-10',
      updatedAt: '2026-03-12',
      description: '<p>Build and maintain internal HR products, REST APIs and integration services.</p><p>Work closely with HR, product and frontend on candidate screening flows.</p>',
      requirementNotes: '<p>Priority role for Q2 delivery. Strong ownership and API design mindset matter.</p>',
      requirements: [
        { label: 'Laravel', type: 'skill', required: true },
        { label: 'PHP', type: 'skill', required: true },
        { label: 'PostgreSQL', type: 'skill', required: true },
        { label: 'REST API', type: 'skill', required: true },
        { label: 'Docker', type: 'tool', required: false },
        { label: 'English B2', type: 'language', required: false }
      ]
    },
    {
      id: 'vac-002',
      title: 'Product Designer',
      department: 'Product',
      employmentType: 'Full-time',
      workMode: 'Hybrid',
      seniority: 'Senior',
      minSalary: '2200',
      maxSalary: '3200',
      currency: 'AZN',
      location: 'Baku',
      closeDate: '2026-03-23',
      status: 'paused',
      applicants: 23,
      interviews: 2,
      createdAt: '2026-03-08',
      updatedAt: '2026-03-11',
      description: '<p>Own UX flows for candidate screening and recruiter dashboards.</p>',
      requirementNotes: '<p>Portfolio quality is a stronger signal than tool count.</p>',
      requirements: [
        { label: 'Figma', type: 'skill', required: true },
        { label: 'Design Systems', type: 'skill', required: true },
        { label: 'Prototyping', type: 'skill', required: true },
        { label: 'UX Writing', type: 'skill', required: false }
      ]
    },
    {
      id: 'vac-003',
      title: 'HR Operations Specialist',
      department: 'HR',
      employmentType: 'Full-time',
      workMode: 'Onsite',
      seniority: 'Middle',
      minSalary: '1400',
      maxSalary: '1900',
      currency: 'AZN',
      location: 'Baku',
      closeDate: '2026-04-03',
      status: 'draft',
      applicants: 0,
      interviews: 0,
      createdAt: '2026-03-12',
      updatedAt: '2026-03-12',
      description: '<p>Support leave, attendance and employee document workflows.</p>',
      requirementNotes: '<p>Prefer candidates with HRIS experience.</p>',
      requirements: [
        { label: 'HRIS', type: 'tool', required: true },
        { label: 'Reporting', type: 'skill', required: true },
        { label: 'Excel', type: 'skill', required: true }
      ]
    },
    {
      id: 'vac-004',
      title: 'Data Analyst',
      department: 'Analytics',
      employmentType: 'Full-time',
      workMode: 'Remote',
      seniority: 'Middle',
      minSalary: '1800',
      maxSalary: '2600',
      currency: 'AZN',
      location: 'Remote',
      closeDate: '2026-03-30',
      status: 'published',
      applicants: 31,
      interviews: 1,
      createdAt: '2026-03-06',
      updatedAt: '2026-03-10',
      description: '<p>Analyze hiring funnel and performance metrics, prepare BI-ready exports.</p>',
      requirementNotes: '<p>Strong SQL is mandatory. Python can be learned on the job.</p>',
      requirements: [
        { label: 'SQL', type: 'skill', required: true },
        { label: 'Python', type: 'skill', required: false },
        { label: 'Tableau', type: 'tool', required: false },
        { label: 'Statistics', type: 'skill', required: true }
      ]
    }
  ];
}

function getVacancyStore() {
  const raw = localStorage.getItem(VACANCY_STORAGE_KEY);
  if (raw) {
    return JSON.parse(raw);
  }

  const seed = getSeedVacancies();
  saveVacancyStore(seed);
  return seed;
}

function saveVacancyStore(items) {
  localStorage.setItem(VACANCY_STORAGE_KEY, JSON.stringify(items));
}

function getVacancyById(vacancyId) {
  return getVacancyStore().find(item => item.id === vacancyId) || null;
}

function getCvStore() {
  const raw = localStorage.getItem(CV_STORAGE_KEY);
  return raw ? JSON.parse(raw) : {};
}

function saveCvStore(items) {
  localStorage.setItem(CV_STORAGE_KEY, JSON.stringify(items));
}

function getInterviewRecords() {
  const cvStore = getCvStore();
  const vacancies = getVacancyStore();
  const vacancyMap = new Map(vacancies.map(item => [item.id, item]));
  const output = [];

  Object.entries(cvStore).forEach(([vacancyId, state]) => {
    const vacancy = vacancyMap.get(vacancyId) || getSeedVacancies()[0];
    const candidates = Array.isArray(state?.candidates) ? state.candidates : [];

    candidates.forEach((candidate, index) => {
      const normalized = {
        name: candidate?.name || `Candidate ${index + 1}`,
        email: candidate?.email || 'unknown@mail.az',
        score: typeof candidate?.score === 'number' ? candidate.score : 0,
        statusKey: candidate?.statusKey || 'ai_analyzed',
        summary: candidate?.summary || '',
        source: candidate?.source || 'Direct CV upload',
        history: Array.isArray(candidate?.history) ? candidate.history : [],
        interview: candidate?.interview || null
      };

      if (!normalized.interview && !['interview_scheduled', 'interviewed', 'rejected'].includes(normalized.statusKey)) {
        return;
      }

      if (!normalized.interview && normalized.statusKey === 'rejected') {
        return;
      }

      output.push({
        vacancyId,
        vacancyTitle: vacancy?.title || 'Unknown Vacancy',
        candidateIndex: index,
        candidateName: normalized.name,
        candidateEmail: normalized.email,
        score: normalized.score,
        statusKey: normalized.statusKey,
        source: normalized.source,
        history: normalized.history,
        interviewType: normalized.interview?.type || 'Interview',
        interviewDate: normalized.interview?.date || '',
        interviewer: normalized.interview?.interviewer || 'Unassigned',
        note: normalized.interview?.note || '',
        stageLabel: normalized.statusKey === 'interview_scheduled'
          ? 'Scheduled'
          : normalized.statusKey === 'interviewed'
            ? 'Completed'
            : 'Decision logged'
      });
    });
  });

  return output.sort((left, right) => {
    const leftTime = left.interviewDate ? new Date(left.interviewDate).getTime() : Number.MAX_SAFE_INTEGER;
    const rightTime = right.interviewDate ? new Date(right.interviewDate).getTime() : Number.MAX_SAFE_INTEGER;
    return leftTime - rightTime;
  });
}

function getTalentPoolRecords() {
  const cvStore = getCvStore();
  const vacancies = getVacancyStore();
  const vacancyMap = new Map(vacancies.map(item => [item.id, item]));
  const output = [];

  Object.entries(cvStore).forEach(([vacancyId, state]) => {
    const vacancy = vacancyMap.get(vacancyId) || getSeedVacancies()[0];
    const candidates = Array.isArray(state?.candidates) ? state.candidates : [];

    candidates.forEach((candidate, index) => {
      if (!candidate?.talentPool?.saved) {
        return;
      }

      output.push({
        vacancyId,
        vacancyTitle: vacancy?.title || 'Unknown Vacancy',
        candidateIndex: index,
        name: candidate?.name || `Candidate ${index + 1}`,
        email: candidate?.email || 'unknown@mail.az',
        score: typeof candidate?.score === 'number' ? candidate.score : 0,
        skills: Array.isArray(candidate?.skills) ? candidate.skills : [],
        gaps: Array.isArray(candidate?.gaps) ? candidate.gaps : [],
        experience: candidate?.experience || '-',
        statusKey: candidate?.statusKey || 'ai_analyzed',
        summary: candidate?.summary || '',
        source: candidate?.source || 'Direct CV upload',
        history: Array.isArray(candidate?.history) ? candidate.history : [],
        interview: candidate?.interview || null,
        talentPool: candidate.talentPool
      });
    });
  });

  return output.sort((left, right) => right.score - left.score);
}

function escapeHtml(value) {
  return String(value || '')
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#39;');
}

function slugify(value) {
  return String(value || '')
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '')
    .slice(0, 40);
}

function formatDate(dateValue) {
  if (!dateValue) {
    return '-';
  }

  return new Date(dateValue).toLocaleDateString('en-GB', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  });
}

function collectVacancyStats(items) {
  return {
    total: items.length,
    published: items.filter(item => item.status === 'published').length,
    draft: items.filter(item => item.status === 'draft').length,
    applicants: items.reduce((sum, item) => sum + Number(item.applicants || 0), 0),
    interviews: items.reduce((sum, item) => sum + Number(item.interviews || 0), 0)
  };
}

function initVacanciesPage() {
  const root = document.getElementById('vacancyDashboard');
  if (!root) {
    return;
  }

  const state = {
    query: '',
    department: 'all',
    status: 'all'
  };

  const render = () => {
    const items = getVacancyStore();
    const filtered = items.filter(item => {
      const matchesQuery = !state.query || [item.title, item.department, item.location].join(' ').toLowerCase().includes(state.query.toLowerCase());
      const matchesDepartment = state.department === 'all' || item.department === state.department;
      const matchesStatus = state.status === 'all' || item.status === state.status;
      return matchesQuery && matchesDepartment && matchesStatus;
    });

    const stats = collectVacancyStats(items);
    const departments = [...new Set(items.map(item => item.department))];

    root.innerHTML = `
      <section class="space-y-6">
        <div class="vacancy-hero">
          <div>
            <div class="eyebrow">Recruitment core</div>
            <h1 class="vacancy-hero-title">Vacancy pipeline without backend dependency</h1>
            <p class="vacancy-hero-copy">Create, edit and remove vacancies with mock persistence. Requirements are built as taggable chips so HR can move faster before engineering wiring starts.</p>
          </div>
          <div class="vacancy-hero-actions">
            <a class="btn-primary" href="vacancy-create.html">Create vacancy</a>
            <button class="btn-ghost" type="button" data-action="reset-vacancies">Reset mock data</button>
          </div>
        </div>

        <div class="grid grid-cols-5 gap-4">
          <div class="stat-card">
            <div class="text-xs text-slate-500">Total vacancies</div>
            <div class="mt-2 text-2xl font-display font-700 text-white">${stats.total}</div>
          </div>
          <div class="stat-card">
            <div class="text-xs text-slate-500">Published</div>
            <div class="mt-2 text-2xl font-display font-700 text-white">${stats.published}</div>
          </div>
          <div class="stat-card">
            <div class="text-xs text-slate-500">Draft / paused</div>
            <div class="mt-2 text-2xl font-display font-700 text-white">${stats.draft + items.filter(item => item.status === 'paused').length}</div>
          </div>
          <div class="stat-card">
            <div class="text-xs text-slate-500">Applicants in mock state</div>
            <div class="mt-2 text-2xl font-display font-700 text-white">${stats.applicants}</div>
          </div>
          <div class="stat-card">
            <div class="text-xs text-slate-500">Interview steps</div>
            <div class="mt-2 text-2xl font-display font-700 text-white">${stats.interviews}</div>
          </div>
        </div>

        <div class="card">
          <div class="vacancy-toolbar">
            <div class="vacancy-toolbar-group">
              <input class="input" id="vacancySearch" placeholder="Search title, department, location" value="${escapeHtml(state.query)}">
            </div>
            <div class="vacancy-toolbar-group vacancy-toolbar-slim">
              <select class="input" id="vacancyDepartment">
                <option value="all">All departments</option>
                ${departments.map(item => `<option value="${escapeHtml(item)}"${state.department === item ? ' selected' : ''}>${escapeHtml(item)}</option>`).join('')}
              </select>
            </div>
            <div class="vacancy-toolbar-group vacancy-toolbar-slim">
              <select class="input" id="vacancyStatus">
                <option value="all">All statuses</option>
                ${Object.keys(VACANCY_STATUS_META).map(key => `<option value="${key}"${state.status === key ? ' selected' : ''}>${VACANCY_STATUS_META[key].label}</option>`).join('')}
              </select>
            </div>
          </div>
        </div>

        <div class="vacancy-board">
          ${filtered.map(item => renderVacancyCard(item)).join('')}
        </div>
      </section>
    `;

    const search = root.querySelector('#vacancySearch');
    const department = root.querySelector('#vacancyDepartment');
    const status = root.querySelector('#vacancyStatus');

    search.addEventListener('input', event => {
      state.query = event.target.value;
      render();
    });

    department.addEventListener('change', event => {
      state.department = event.target.value;
      render();
    });

    status.addEventListener('change', event => {
      state.status = event.target.value;
      render();
    });

    root.querySelector('[data-action="reset-vacancies"]').addEventListener('click', () => {
      saveVacancyStore(getSeedVacancies());
      render();
    });

    root.querySelectorAll('[data-action="delete-vacancy"]').forEach(button => {
      button.addEventListener('click', event => {
        const vacancyId = event.currentTarget.dataset.id;
        const current = getVacancyStore();
        const target = current.find(item => item.id === vacancyId);
        if (!target) {
          return;
        }

        const confirmed = window.confirm(`Delete mock vacancy "${target.title}"?`);
        if (!confirmed) {
          return;
        }

        saveVacancyStore(current.filter(item => item.id !== vacancyId));
        render();
      });
    });

    root.querySelectorAll('[data-action="duplicate-vacancy"]').forEach(button => {
      button.addEventListener('click', event => {
        const vacancyId = event.currentTarget.dataset.id;
        const current = getVacancyStore();
        const target = current.find(item => item.id === vacancyId);
        if (!target) {
          return;
        }

        const clone = {
          ...target,
          id: `vac-${Date.now()}`,
          title: `${target.title} Copy`,
          status: 'draft',
          applicants: 0,
          interviews: 0,
          createdAt: new Date().toISOString().slice(0, 10),
          updatedAt: new Date().toISOString().slice(0, 10)
        };

        saveVacancyStore([clone, ...current]);
        render();
      });
    });
  };

  render();
}

function renderVacancyCard(item) {
  const meta = VACANCY_STATUS_META[item.status] || VACANCY_STATUS_META.draft;
  const required = item.requirements.filter(requirement => requirement.required).slice(0, 4);
  return `
    <article class="card vacancy-card">
      <div class="vacancy-card-top">
        <div>
          <div class="text-xs text-slate-500">${escapeHtml(item.department)} / ${escapeHtml(item.workMode)} / ${escapeHtml(item.employmentType)}</div>
          <h3 class="vacancy-card-title">${escapeHtml(item.title)}</h3>
          <p class="text-sm text-slate-400">${escapeHtml(item.location)} / ${escapeHtml(item.seniority)} / closes ${formatDate(item.closeDate)}</p>
        </div>
        <span class="badge ${meta.badge}">${meta.label}</span>
      </div>
      <div class="vacancy-card-metrics">
        <div><strong>${escapeHtml(item.applicants)}</strong><span>Applicants</span></div>
        <div><strong>${escapeHtml(item.interviews)}</strong><span>Interviews</span></div>
        <div><strong>${escapeHtml(item.status === 'published' ? 'Live' : item.status === 'paused' ? 'Paused' : item.status === 'closed' ? 'Closed' : 'Draft')}</strong><span>Pipeline</span></div>
      </div>
      <div class="vacancy-card-tags">
        ${required.map(requirement => `<span class="vacancy-chip is-required">${escapeHtml(requirement.label)}</span>`).join('')}
      </div>
      <p class="vacancy-card-copy">${stripHtml(item.description).slice(0, 140)}${stripHtml(item.description).length > 140 ? '...' : ''}</p>
      <div class="vacancy-card-actions">
        <a class="btn-primary" href="candidates.html?vacancy=${encodeURIComponent(item.id)}">Candidates</a>
        <a class="btn-ghost" href="vacancy-create.html?id=${encodeURIComponent(item.id)}">Edit</a>
        <button class="btn-ghost" type="button" data-action="duplicate-vacancy" data-id="${escapeHtml(item.id)}">Duplicate</button>
        <button class="btn-danger" type="button" data-action="delete-vacancy" data-id="${escapeHtml(item.id)}">Delete</button>
      </div>
    </article>
  `;
}

function stripHtml(value) {
  const temp = document.createElement('div');
  temp.innerHTML = value || '';
  return temp.textContent || temp.innerText || '';
}

function initVacancyBuilderPage() {
  const root = document.getElementById('vacancyBuilder');
  if (!root) {
    return;
  }

  const params = new URLSearchParams(window.location.search);
  const vacancyId = params.get('id');
  const existing = vacancyId ? getVacancyById(vacancyId) : null;
  const baseState = existing || {
    id: '',
    title: '',
    department: 'IT',
    employmentType: 'Full-time',
    workMode: 'Hybrid',
    seniority: 'Middle',
    minSalary: '',
    maxSalary: '',
    currency: 'AZN',
    location: 'Baku',
    closeDate: '',
    status: 'draft',
    applicants: 0,
    interviews: 0,
    description: '<p>Describe role mission, scope and team context.</p>',
    requirementNotes: '<p>Write AI-facing notes, constraints or red flags.</p>',
    requirements: [
      { label: 'Laravel', type: 'skill', required: true },
      { label: 'PostgreSQL', type: 'skill', required: true }
    ]
  };

  const state = structuredClone(baseState);
  const requirementPresets = [
    { label: 'Laravel', type: 'skill' },
    { label: 'PHP', type: 'skill' },
    { label: 'PostgreSQL', type: 'skill' },
    { label: 'REST API', type: 'skill' },
    { label: 'Docker', type: 'tool' },
    { label: 'AWS', type: 'tool' },
    { label: 'English B2', type: 'language' },
    { label: '3+ years backend', type: 'experience' },
    { label: 'Figma', type: 'skill' },
    { label: 'SQL', type: 'skill' }
  ];

  const render = () => {
    root.innerHTML = `
      <section class="space-y-6">
        <div class="vacancy-hero vacancy-hero-tight">
          <div>
            <div class="eyebrow">${existing ? 'Edit mock vacancy' : 'Create mock vacancy'}</div>
            <h1 class="vacancy-hero-title">${existing ? 'Update vacancy and requirement logic' : 'Set up vacancy and AI-ready requirements'}</h1>
            <p class="vacancy-hero-copy">This page keeps everything local. Use it to validate which fields HR really needs before backend implementation starts.</p>
          </div>
          <div class="vacancy-hero-actions">
            <a class="btn-ghost" href="vacancies.html">Back to list</a>
            <button class="btn-primary" type="button" data-action="save-vacancy">${existing ? 'Save changes' : 'Create vacancy'}</button>
          </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
          <div class="card">
            <div class="label">Live status</div>
            <div class="text-lg font-display font-700 text-white">${VACANCY_STATUS_META[state.status].label}</div>
            <div class="text-xs text-slate-500 mt-1">Saved only in browser localStorage.</div>
          </div>
          <div class="card">
            <div class="label">Required tags</div>
            <div class="text-lg font-display font-700 text-white">${state.requirements.filter(item => item.required).length}</div>
            <div class="text-xs text-slate-500 mt-1">AI score weights can come later.</div>
          </div>
          <div class="card">
            <div class="label">Preview route</div>
            <div class="text-lg font-display font-700 text-white">Candidate pipeline</div>
            <div class="text-xs text-slate-500 mt-1">After save you can open AI analysis and interview flow from vacancy cards.</div>
          </div>
        </div>

        <div class="card">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="label" for="vacancyTitle">Title</label>
              <input class="input" id="vacancyTitle" value="${escapeHtml(state.title)}" placeholder="Backend Developer">
            </div>
            <div>
              <label class="label" for="vacancyDepartmentInput">Department</label>
              <select class="input" id="vacancyDepartmentInput">
                ${['IT', 'Product', 'HR', 'Analytics', 'Finance'].map(item => `<option value="${item}"${state.department === item ? ' selected' : ''}>${item}</option>`).join('')}
              </select>
            </div>
            <div>
              <label class="label" for="vacancyEmploymentType">Employment type</label>
              <select class="input" id="vacancyEmploymentType">
                ${['Full-time', 'Part-time', 'Contract'].map(item => `<option value="${item}"${state.employmentType === item ? ' selected' : ''}>${item}</option>`).join('')}
              </select>
            </div>
            <div>
              <label class="label" for="vacancyWorkMode">Work mode</label>
              <select class="input" id="vacancyWorkMode">
                ${['Onsite', 'Hybrid', 'Remote'].map(item => `<option value="${item}"${state.workMode === item ? ' selected' : ''}>${item}</option>`).join('')}
              </select>
            </div>
            <div>
              <label class="label" for="vacancySeniority">Seniority</label>
              <select class="input" id="vacancySeniority">
                ${['Junior', 'Middle', 'Senior', 'Lead'].map(item => `<option value="${item}"${state.seniority === item ? ' selected' : ''}>${item}</option>`).join('')}
              </select>
            </div>
            <div>
              <label class="label" for="vacancyStatusInput">Status</label>
              <select class="input" id="vacancyStatusInput">
                ${Object.entries(VACANCY_STATUS_META).map(([key, meta]) => `<option value="${key}"${state.status === key ? ' selected' : ''}>${meta.label}</option>`).join('')}
              </select>
            </div>
            <div>
              <label class="label" for="vacancyLocation">Location</label>
              <input class="input" id="vacancyLocation" value="${escapeHtml(state.location)}" placeholder="Baku">
            </div>
            <div>
              <label class="label" for="vacancyCloseDate">Close date</label>
              <input class="input" type="date" id="vacancyCloseDate" value="${escapeHtml(state.closeDate)}">
            </div>
            <div class="grid grid-cols-3 gap-3 col-span-2">
              <div>
                <label class="label" for="vacancyMinSalary">Min salary</label>
                <input class="input" id="vacancyMinSalary" value="${escapeHtml(state.minSalary)}" placeholder="2500">
              </div>
              <div>
                <label class="label" for="vacancyMaxSalary">Max salary</label>
                <input class="input" id="vacancyMaxSalary" value="${escapeHtml(state.maxSalary)}" placeholder="4000">
              </div>
              <div>
                <label class="label" for="vacancyCurrency">Currency</label>
                <select class="input" id="vacancyCurrency">
                  ${['AZN', 'USD', 'EUR'].map(item => `<option value="${item}"${state.currency === item ? ' selected' : ''}>${item}</option>`).join('')}
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div class="card">
            <div class="vacancy-section-header">
              <div>
                <div class="text-base font-display font-700 text-white">Description</div>
                <div class="text-xs text-slate-500">Recruiter-facing role context.</div>
              </div>
              <div class="editor-toolbar">
                <button type="button" class="editor-btn" data-command="bold" data-target="descriptionEditor">B</button>
                <button type="button" class="editor-btn" data-command="insertUnorderedList" data-target="descriptionEditor">List</button>
              </div>
            </div>
            <div class="editor-surface" id="descriptionEditor" contenteditable="true">${state.description}</div>
          </div>
          <div class="card">
            <div class="vacancy-section-header">
              <div>
                <div class="text-base font-display font-700 text-white">Requirements notes</div>
                <div class="text-xs text-slate-500">AI-facing clarifications, exceptions and hiring signals.</div>
              </div>
              <div class="editor-toolbar">
                <button type="button" class="editor-btn" data-command="bold" data-target="notesEditor">B</button>
                <button type="button" class="editor-btn" data-command="insertUnorderedList" data-target="notesEditor">List</button>
              </div>
            </div>
            <div class="editor-surface" id="notesEditor" contenteditable="true">${state.requirementNotes}</div>
          </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
          <div class="card col-span-2">
            <div class="vacancy-section-header">
              <div>
                <div class="text-base font-display font-700 text-white">Structured requirements</div>
                <div class="text-xs text-slate-500">Tag required vs preferred items. This is less tiring for HR than typing a long block every time.</div>
              </div>
            </div>
            <div class="tag-builder">
              <input class="input" id="requirementInput" placeholder="Type a requirement and press Enter">
              <select class="input" id="requirementType">
                ${['skill', 'tool', 'language', 'experience', 'education'].map(item => `<option value="${item}">${item}</option>`).join('')}
              </select>
              <label class="tag-toggle"><input type="checkbox" id="requirementRequired" checked> Required</label>
              <button class="btn-primary" type="button" data-action="add-requirement">Add tag</button>
            </div>
            <div class="preset-row">
              ${requirementPresets.map(item => `<button class="preset-chip" type="button" data-preset="${escapeHtml(item.label)}" data-type="${item.type}">${escapeHtml(item.label)}</button>`).join('')}
            </div>
            <div class="requirement-list">
              ${state.requirements.map((item, index) => `
                <div class="requirement-pill ${item.required ? 'is-required' : 'is-optional'}">
                  <div>
                    <strong>${escapeHtml(item.label)}</strong>
                    <span>${escapeHtml(item.type)} / ${item.required ? 'required' : 'preferred'}</span>
                  </div>
                  <button type="button" data-action="remove-requirement" data-index="${index}">Remove</button>
                </div>
              `).join('')}
            </div>
          </div>
          <div class="card">
            <div class="vacancy-section-header">
              <div>
                <div class="text-base font-display font-700 text-white">Preview</div>
                <div class="text-xs text-slate-500">What the vacancy card will expose first.</div>
              </div>
            </div>
            <div class="vacancy-preview">
              <div class="badge ${VACANCY_STATUS_META[state.status].badge}">${VACANCY_STATUS_META[state.status].label}</div>
              <h3>${escapeHtml(state.title || 'Untitled vacancy')}</h3>
              <p>${escapeHtml(state.department)} / ${escapeHtml(state.workMode)} / ${escapeHtml(state.employmentType)}</p>
              <div class="vacancy-card-tags">
                ${state.requirements.slice(0, 5).map(item => `<span class="vacancy-chip ${item.required ? 'is-required' : ''}">${escapeHtml(item.label)}</span>`).join('')}
              </div>
              <div class="preview-meta">
                <span>${escapeHtml(state.location || 'Location TBD')}</span>
                <span>${state.minSalary || '-'}${state.maxSalary ? ` - ${state.maxSalary}` : ''} ${escapeHtml(state.currency)}</span>
              </div>
            </div>
          </div>
        </div>
      </section>
    `;

    bindBuilderEvents();
  };

  const syncStateFromInputs = () => {
    state.title = root.querySelector('#vacancyTitle').value.trim();
    state.department = root.querySelector('#vacancyDepartmentInput').value;
    state.employmentType = root.querySelector('#vacancyEmploymentType').value;
    state.workMode = root.querySelector('#vacancyWorkMode').value;
    state.seniority = root.querySelector('#vacancySeniority').value;
    state.status = root.querySelector('#vacancyStatusInput').value;
    state.location = root.querySelector('#vacancyLocation').value.trim();
    state.closeDate = root.querySelector('#vacancyCloseDate').value;
    state.minSalary = root.querySelector('#vacancyMinSalary').value.trim();
    state.maxSalary = root.querySelector('#vacancyMaxSalary').value.trim();
    state.currency = root.querySelector('#vacancyCurrency').value;
    state.description = root.querySelector('#descriptionEditor').innerHTML.trim();
    state.requirementNotes = root.querySelector('#notesEditor').innerHTML.trim();
  };

  const addRequirement = (label, type, required) => {
    const normalized = label.trim();
    if (!normalized) {
      return;
    }

    const alreadyExists = state.requirements.some(item => item.label.toLowerCase() === normalized.toLowerCase());
    if (alreadyExists) {
      return;
    }

    state.requirements.push({
      label: normalized,
      type,
      required
    });
    render();
  };

  const bindBuilderEvents = () => {
    root.querySelectorAll('input, select').forEach(element => {
      element.addEventListener('input', () => {
        syncStateFromInputs();
      });
      element.addEventListener('change', () => {
        syncStateFromInputs();
        render();
      });
    });

    root.querySelectorAll('.editor-surface').forEach(element => {
      element.addEventListener('input', () => {
        syncStateFromInputs();
      });
    });

    root.querySelectorAll('.editor-btn').forEach(button => {
      button.addEventListener('click', event => {
        const target = root.querySelector(`#${event.currentTarget.dataset.target}`);
        target.focus();
        document.execCommand(event.currentTarget.dataset.command, false);
        syncStateFromInputs();
      });
    });

    root.querySelector('[data-action="add-requirement"]').addEventListener('click', () => {
      const input = root.querySelector('#requirementInput');
      const type = root.querySelector('#requirementType').value;
      const required = root.querySelector('#requirementRequired').checked;
      addRequirement(input.value, type, required);
    });

    root.querySelector('#requirementInput').addEventListener('keydown', event => {
      if (event.key === 'Enter') {
        event.preventDefault();
        const type = root.querySelector('#requirementType').value;
        const required = root.querySelector('#requirementRequired').checked;
        addRequirement(event.currentTarget.value, type, required);
      }
    });

    root.querySelectorAll('[data-preset]').forEach(button => {
      button.addEventListener('click', event => {
        addRequirement(event.currentTarget.dataset.preset, event.currentTarget.dataset.type, true);
      });
    });

    root.querySelectorAll('[data-action="remove-requirement"]').forEach(button => {
      button.addEventListener('click', event => {
        const index = Number(event.currentTarget.dataset.index);
        state.requirements.splice(index, 1);
        render();
      });
    });

    root.querySelector('[data-action="save-vacancy"]').addEventListener('click', () => {
      syncStateFromInputs();
      const current = getVacancyStore();
      const payload = {
        ...state,
        id: state.id || `vac-${slugify(state.title || 'new')}-${Date.now()}`,
        createdAt: existing ? existing.createdAt : new Date().toISOString().slice(0, 10),
        updatedAt: new Date().toISOString().slice(0, 10),
        applicants: existing ? existing.applicants : 0,
        interviews: existing ? existing.interviews : 0
      };
      const next = existing
        ? current.map(item => item.id === existing.id ? payload : item)
        : [payload, ...current];
      saveVacancyStore(next);
      window.location.href = 'vacancies.html';
    });
  };

  render();
}

function initCandidatesPage() {
  const params = new URLSearchParams(window.location.search);
  const vacancyId = params.get('vacancy') || 'vac-001';
  const vacancy = getVacancyById(vacancyId) || getSeedVacancies()[0];
  const fileInput = document.getElementById('candidateFileInput');
  const uploadModal = document.getElementById('candidateUploadModal');
  const analyzeModal = document.getElementById('candidateAnalyzeModal');
  const profileModal = document.getElementById('candidateProfileModal');
  const interviewModal = document.getElementById('candidateInterviewModal');
  const talentModal = document.getElementById('candidateTalentModal');
  const rejectModal = document.getElementById('candidateRejectModal');
  const offerModal = document.getElementById('candidateOfferModal');
  const queueList = document.getElementById('candidateUploadQueue');
  const uploadedCvList = document.getElementById('uploadedCvList');
  const analyzeList = document.getElementById('candidateAnalyzeList');
  const analyzeProgress = document.getElementById('candidateAnalyzeProgress');
  const uploadCount = document.getElementById('candidateCount');
  const uploadStatus = document.getElementById('candidatePageStatus');
  const vacancyTitle = document.getElementById('candidateVacancyTitle');
  const aiHint = document.getElementById('candidateAiHint');
  const tableBody = document.getElementById('candidateResultsBody');
  const openButtons = document.querySelectorAll('[data-action="open-upload-modal"]');
  const closeButtons = document.querySelectorAll('[data-action="close-upload-modal"]');
  const closeAnalyzeButtons = document.querySelectorAll('[data-action="close-analyze-modal"]');
  const closeProfileButtons = document.querySelectorAll('[data-action="close-profile-modal"]');
  const closeInterviewButtons = document.querySelectorAll('[data-action="close-interview-modal"]');
  const closeTalentButtons = document.querySelectorAll('[data-action="close-talent-modal"]');
  const closeRejectButtons = document.querySelectorAll('[data-action="close-reject-modal"]');
  const closeOfferButtons = document.querySelectorAll('[data-action="close-offer-modal"]');
  const clearQueueButton = document.getElementById('clearCandidateQueue');
  const uploadQueueButton = document.getElementById('confirmCandidateUpload');
  const analyzeButton = document.getElementById('runCandidateAnalysis');
  const confirmAnalysisButton = document.getElementById('confirmCandidateAnalysis');
  const selectAllAnalyzeFilesButton = document.getElementById('selectAllAnalyzeFiles');
  const loadSampleButton = document.getElementById('loadCandidateSample');
  const dropzone = document.getElementById('candidateDropzone');
  const profileName = document.getElementById('candidateProfileName');
  const profileMeta = document.getElementById('candidateProfileMeta');
  const profileScore = document.getElementById('candidateProfileScore');
  const profileSummary = document.getElementById('candidateProfileSummary');
  const profileStatus = document.getElementById('candidateProfileStatus');
  const profileExperience = document.getElementById('candidateProfileExperience');
  const profileSkills = document.getElementById('candidateProfileSkills');
  const profileGaps = document.getElementById('candidateProfileGaps');
  const profileHistory = document.getElementById('candidateProfileHistory');
  const talentAction = document.getElementById('candidateTalentAction');
  const rejectAction = document.getElementById('candidateRejectAction');
  const offerAction = document.getElementById('candidateOfferAction');
  const interviewAction = document.getElementById('candidateInterviewAction');
  const interviewTitle = document.getElementById('candidateInterviewTitle');
  const interviewSubtitle = document.getElementById('candidateInterviewSubtitle');
  const interviewTypeInput = document.getElementById('candidateInterviewType');
  const interviewDateInput = document.getElementById('candidateInterviewDate');
  const interviewInterviewerInput = document.getElementById('candidateInterviewInterviewer');
  const interviewOutcomeInput = document.getElementById('candidateInterviewOutcome');
  const interviewNoteInput = document.getElementById('candidateInterviewNote');
  const interviewSaveButton = document.getElementById('candidateInterviewSave');
  const talentTitle = document.getElementById('candidateTalentTitle');
  const talentNoteInput = document.getElementById('candidateTalentNote');
  const talentSaveButton = document.getElementById('candidateTalentSave');
  const rejectTitle = document.getElementById('candidateRejectTitle');
  const rejectReasonInput = document.getElementById('candidateRejectReason');
  const rejectNoteInput = document.getElementById('candidateRejectNote');
  const rejectSaveButton = document.getElementById('candidateRejectSave');
  const offerTitle = document.getElementById('candidateOfferTitle');
  const offerOutcomeInput = document.getElementById('candidateOfferOutcome');
  const offerCompInput = document.getElementById('candidateOfferComp');
  const offerNoteInput = document.getElementById('candidateOfferNote');
  const offerSaveButton = document.getElementById('candidateOfferSave');

  if (!fileInput || !uploadModal || !analyzeModal || !profileModal || !interviewModal || !talentModal || !rejectModal || !offerModal || !queueList || !uploadedCvList || !analyzeList || !analyzeProgress || !tableBody || !confirmAnalysisButton || !selectAllAnalyzeFilesButton || !profileName || !profileMeta || !profileScore || !profileSummary || !profileStatus || !profileExperience || !profileSkills || !profileGaps || !profileHistory || !talentAction || !rejectAction || !offerAction || !interviewAction || !interviewTitle || !interviewSubtitle || !interviewTypeInput || !interviewDateInput || !interviewInterviewerInput || !interviewOutcomeInput || !interviewNoteInput || !interviewSaveButton || !talentTitle || !talentNoteInput || !talentSaveButton || !rejectTitle || !rejectReasonInput || !rejectNoteInput || !rejectSaveButton || !offerTitle || !offerOutcomeInput || !offerCompInput || !offerNoteInput || !offerSaveButton) {
    return;
  }

  let activeCandidateIndex = null;

  vacancyTitle.textContent = `Candidates - ${vacancy.title}`;

  const getState = () => {
    const store = getCvStore();
    if (!store[vacancyId]) {
      store[vacancyId] = { files: [], candidates: [] };
      saveCvStore(store);
    }
    return store[vacancyId];
  };

  const saveState = (state) => {
    const store = getCvStore();
    store[vacancyId] = state;
    saveCvStore(store);
  };

  let queue = [];

  const formatFileSize = (bytes) => {
    if (!bytes) {
      return '0 KB';
    }
    if (bytes >= 1024 * 1024) {
      return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
    }
    return `${Math.max(1, Math.round(bytes / 1024))} KB`;
  };

  const statusMeta = (status) => {
    const map = {
      pending: { label: 'pending', className: 'pending' },
      processing: { label: 'processing', className: 'processing' },
      parsed: { label: 'parsed', className: 'parsed' },
      parsed_batch: { label: 'parsed batch', className: 'parsed_batch' },
      failed: { label: 'failed', className: 'failed' },
      queued_for_unpack: { label: 'queued for unpack', className: 'pending' }
    };
    return map[status] || { label: status || 'pending', className: 'pending' };
  };

  const candidateStatusMeta = (statusKey) => {
    const map = {
      ai_analyzed: { label: 'CV Kechdi', className: 'badge-yellow' },
      interview_scheduled: { label: 'Interview Scheduled', className: 'badge-cyan' },
      interviewed: { label: 'Interviewed', className: 'badge-green' },
      offer_pending: { label: 'Offer Pending', className: 'badge-blue' },
      hired: { label: 'Hired', className: 'badge-green' },
      rejected: { label: 'Rejected', className: 'badge-red' }
    };
    return map[statusKey] || map.ai_analyzed;
  };

  const extractDisplayName = (fileName, fallbackIndex) => {
    const base = fileName.replace(/\.[^.]+$/, '').replace(/[-_]+/g, ' ').trim();
    const parts = base.split(/\s+/).filter(Boolean);
    if (!parts.length) {
      return `Candidate ${fallbackIndex + 1}`;
    }
    return parts.map(part => part.charAt(0).toUpperCase() + part.slice(1)).join(' ');
  };

  const generateCandidateRows = (files) => {
    const skillSets = [
      ['Laravel', 'PHP', 'REST API'],
      ['PostgreSQL', 'Docker', 'Git'],
      ['Node.js', 'TypeScript', 'API Design'],
      ['Python', 'SQL', 'Analytics']
    ];
    const colors = ['#3178f6', '#8b5cf6', '#10b981', '#f59e0b'];

    const output = [];
    files.forEach((file, index) => {
      const batchCount = file.kind === 'zip package' ? 3 : 1;
      for (let offset = 0; offset < batchCount; offset += 1) {
        const seed = output.length + index + offset;
        const score = Math.max(41, 88 - (seed * 7 % 43));
        const statusIndex = seed % colors.length;
        const chosenSkills = skillSets[seed % skillSets.length];
        output.push({
          name: batchCount > 1 ? `${extractDisplayName(file.name, index)} Batch ${offset + 1}` : extractDisplayName(file.name, index),
          email: `${slugify(extractDisplayName(file.name, index)).replace(/-/g, '.')}@mail.az`,
          score,
          scoreColor: colors[statusIndex],
          skills: chosenSkills,
          gaps: ['Docker', 'System Design', 'English B2'].slice(statusIndex, statusIndex + 2),
          experience: `${2 + (seed % 4)} il`,
          statusKey: 'ai_analyzed',
          summary: `AI sees ${extractDisplayName(file.name, index)} as a solid match for ${vacancy.title} with strongest signals around ${chosenSkills.slice(0, 2).join(' and ')}.`,
          source: file.kind === 'zip package' ? 'Batch upload' : 'Direct CV upload',
          history: [
            `Previous review: ${seed % 2 === 0 ? 'No prior process found' : 'Seen in earlier recruiter screening'}`,
            `Recruiter note: ${seed % 3 === 0 ? 'Strong backend baseline, verify communication.' : 'Potential interview candidate if salary aligns.'}`,
            `Risk note: ${seed % 4 === 0 ? 'Compensation expectation unknown.' : 'No major blockers yet.'}`
          ]
        });
      }
    });
    return output.sort((left, right) => right.score - left.score);
  };

  const normalizeCandidate = (candidate) => {
    const safeCandidate = candidate || {};
    return {
      name: safeCandidate.name || 'Unknown Candidate',
      email: safeCandidate.email || 'unknown@mail.az',
      score: typeof safeCandidate.score === 'number' ? safeCandidate.score : 0,
      scoreColor: safeCandidate.scoreColor || '#3178f6',
      skills: Array.isArray(safeCandidate.skills) ? safeCandidate.skills : [],
      gaps: Array.isArray(safeCandidate.gaps) ? safeCandidate.gaps : ['Communication check', 'Compensation unknown'],
      experience: safeCandidate.experience || '-',
      statusKey: safeCandidate.statusKey || 'ai_analyzed',
      summary: safeCandidate.summary || 'AI summary is not available for this older mock record yet.',
      source: safeCandidate.source || 'Direct CV upload',
      history: Array.isArray(safeCandidate.history) && safeCandidate.history.length
        ? safeCandidate.history
        : [
            'Previous review: No prior process found',
            'Recruiter note: Older mock record imported from local browser state'
          ],
      interview: safeCandidate.interview || null,
      offer: safeCandidate.offer || null,
      rejection: safeCandidate.rejection || null,
      talentPool: safeCandidate.talentPool || { saved: false, category: '', note: '', addedAt: '' }
    };
  };

  const renderQueue = () => {
    if (!queue.length) {
      queueList.innerHTML = '<div class="empty-state">No files selected yet.</div>';
      clearQueueButton.disabled = true;
      uploadQueueButton.disabled = true;
      return;
    }

    queueList.innerHTML = queue.map((file, index) => `
      <div class="upload-item">
        <div>
          <strong>${escapeHtml(file.name)}</strong>
          <span>${escapeHtml(file.kind)} / ${formatFileSize(file.size)}</span>
        </div>
        <button type="button" data-action="remove-queued-file" data-index="${index}">Remove</button>
      </div>
    `).join('');

    queueList.querySelectorAll('[data-action="remove-queued-file"]').forEach(button => {
      button.addEventListener('click', event => {
        queue.splice(Number(event.currentTarget.dataset.index), 1);
        renderQueue();
      });
    });

    clearQueueButton.disabled = false;
    uploadQueueButton.disabled = false;
  };

  const renderCandidates = () => {
    const state = getState();
    const candidates = state.candidates;
    uploadCount.textContent = String(candidates.length);

    if (!candidates.length) {
      uploadStatus.innerHTML = `${escapeHtml(vacancy.title)} · <span class="text-brand-400 font-600">${state.files.length} uploaded file</span> · waiting for AI analysis`;
      aiHint.textContent = state.files.length
        ? 'Files are uploaded. Run AI analysis to create candidate rows.'
        : 'No AI result yet. Upload files first.';
      tableBody.innerHTML = `
        <tr>
          <td colspan="6">
            <div class="empty-state">No analyzed candidates yet. Upload CV files and click "AI ilə Analiz Et".</div>
          </td>
        </tr>
      `;
      return;
    }

    uploadStatus.innerHTML = `${escapeHtml(vacancy.title)} · <span class="text-brand-400 font-600">${candidates.length} candidate</span> · AI analizi tamamlandi`;
    aiHint.textContent = `AI recommendation: ${candidates.slice(0, 3).map(item => `${item.name} (${item.score}%)`).join(', ')}`;
    tableBody.innerHTML = candidates.map((rawItem, index) => {
      const item = normalizeCandidate(rawItem);
      const statusInfo = candidateStatusMeta(item.statusKey);
      const circumference = 105.6;
      const dashOffset = (100 - item.score) * circumference / 100;
      const initials = item.name.split(' ').map(part => part.charAt(0)).join('').slice(0, 2).toUpperCase();
      return `
        <tr>
          <td>
            <div class="flex items-center gap-3">
              <div class="avatar bg-gradient-to-br from-brand-600 to-brand-400 text-white text-xs">${escapeHtml(initials)}</div>
              <div>
                <div class="font-600 text-white text-sm">${escapeHtml(item.name)}</div>
                <div class="text-xs text-slate-500">${escapeHtml(item.email)}</div>
              </div>
            </div>
          </td>
          <td>
            <div class="score-ring" style="width:48px;height:48px">
              <svg width="48" height="48" viewBox="0 0 48 48">
                <circle cx="24" cy="24" r="20" fill="none" stroke="#1c2640" stroke-width="4"></circle>
                <circle cx="24" cy="24" r="20" fill="none" stroke="${item.scoreColor}" stroke-width="4" stroke-dasharray="${circumference}" stroke-dashoffset="${dashOffset}" stroke-linecap="round"></circle>
              </svg>
              <div class="score-text"><span class="text-xs font-800 text-white">${item.score}%</span></div>
            </div>
          </td>
          <td><div class="flex flex-wrap gap-1">${item.skills.map(skill => `<span class="badge badge-blue">${escapeHtml(skill)}</span>`).join('')}</div></td>
          <td class="text-slate-300">${escapeHtml(item.experience)}</td>
          <td><span class="badge ${statusInfo.className}">${escapeHtml(statusInfo.label)}</span></td>
          <td><button class="btn-primary text-xs py-1 px-3" data-action="open-profile" data-index="${index}">Profil</button></td>
        </tr>
      `;
    }).join('');

    tableBody.querySelectorAll('[data-action="open-profile"]').forEach(button => {
      button.addEventListener('click', event => {
        const index = Number(event.currentTarget.dataset.index);
        const candidate = normalizeCandidate(candidates[index]);
        if (!candidate) {
          return;
        }
        activeCandidateIndex = index;
        const statusInfo = candidateStatusMeta(candidate.statusKey);

        profileName.textContent = candidate.name;
        profileMeta.textContent = `${candidate.email} / ${candidate.source}`;
        profileScore.textContent = `${candidate.score}%`;
        profileSummary.textContent = candidate.summary;
        profileStatus.innerHTML = `<span class="badge ${statusInfo.className}">${escapeHtml(statusInfo.label)}</span>`;
        profileExperience.textContent = `Experience: ${candidate.experience}`;
        profileSkills.innerHTML = candidate.skills.map(skill => `<span class="vacancy-chip is-required">${escapeHtml(skill)}</span>`).join('');
        profileGaps.innerHTML = candidate.gaps.length
          ? candidate.gaps.map(skill => `<span class="vacancy-chip">${escapeHtml(skill)}</span>`).join('')
          : '<span class="text-xs text-slate-500">No major gaps identified.</span>';
        profileHistory.innerHTML = candidate.history.map(item => `
          <div class="upload-item is-uploaded">
            <div>
              <strong>${escapeHtml(item.split(':')[0])}</strong>
              <span>${escapeHtml(item.split(':').slice(1).join(':').trim() || item)}</span>
            </div>
          </div>
        `).join('');
        talentAction.textContent = candidate.talentPool?.saved ? 'Remove from Talent Pool' : 'Save to Talent Pool';
        rejectAction.disabled = candidate.statusKey === 'rejected';
        offerAction.textContent = candidate.statusKey === 'offer_pending' ? 'Close offer stage' : 'Offer action';
        offerAction.disabled = !['interviewed', 'offer_pending'].includes(candidate.statusKey);
        interviewAction.textContent = candidate.statusKey === 'interview_scheduled' ? 'Close interview stage' : 'Move to interview';
        interviewAction.disabled = ['rejected', 'offer_pending', 'hired'].includes(candidate.statusKey);
        profileModal.classList.add('open');
      });
    });
  };

  const updateCandidate = (index, updater) => {
    const state = getState();
    const target = normalizeCandidate(state.candidates[index]);
    if (!target.name) {
      return;
    }
    state.candidates[index] = updater(target);
    saveState(state);
    renderCandidates();
    if (activeCandidateIndex === index) {
      const refreshed = normalizeCandidate(getState().candidates[index]);
      if (refreshed.name) {
        profileName.textContent = refreshed.name;
      }
    }
  };

  const renderUploadedFiles = () => {
    const state = getState();
    if (!state.files.length) {
      uploadedCvList.innerHTML = '<div class="empty-state">No uploaded CV files yet.</div>';
      return;
    }

    uploadedCvList.innerHTML = state.files.map((file, index) => `
      <div class="upload-item is-uploaded">
        <div>
          <strong>${escapeHtml(file.name)}</strong>
          <span>${escapeHtml(file.kind)} / ${formatFileSize(file.size)}</span>
          <div class="mt-2"><span class="status-pill ${statusMeta(file.parseStatus).className}">${statusMeta(file.parseStatus).label}</span></div>
        </div>
        <div class="flex gap-3 items-center">
          ${file.parseStatus === 'failed' ? `<button type="button" data-action="retry-uploaded-file" data-index="${index}">Retry</button>` : ''}
          <button type="button" data-action="delete-uploaded-file" data-index="${index}">Delete</button>
        </div>
      </div>
    `).join('');

    uploadedCvList.querySelectorAll('[data-action="delete-uploaded-file"]').forEach(button => {
      button.addEventListener('click', event => {
        const state = getState();
        state.files.splice(Number(event.currentTarget.dataset.index), 1);
        state.candidates = [];
        saveState(state);
        renderUploadedFiles();
        renderCandidates();
      });
    });

    uploadedCvList.querySelectorAll('[data-action="retry-uploaded-file"]').forEach(button => {
      button.addEventListener('click', event => {
        const index = Number(event.currentTarget.dataset.index);
        const state = getState();
        const file = state.files[index];
        if (!file) {
          return;
        }

        state.files[index] = { ...file, parseStatus: 'processing' };
        state.candidates = [];
        saveState(state);
        renderUploadedFiles();
        renderCandidates();

        setTimeout(() => {
          const latestState = getState();
          const latestFile = latestState.files[index];
          if (!latestFile) {
            return;
          }

          const retryFailsAgain = latestFile.name.toLowerCase().includes('fail') && Number(latestFile.retryCount || 0) < 1;
          latestState.files[index] = {
            ...latestFile,
            retryCount: Number(latestFile.retryCount || 0) + 1,
            parseStatus: retryFailsAgain ? 'failed' : (latestFile.kind === 'zip package' ? 'parsed_batch' : 'parsed')
          };

          const parsedFiles = latestState.files.filter(item => ['parsed', 'parsed_batch'].includes(item.parseStatus));
          latestState.candidates = parsedFiles.length ? generateCandidateRows(parsedFiles) : [];
          saveState(latestState);
          renderUploadedFiles();
          renderCandidates();
        }, 3000);
      });
    });
  };

  const renderAnalyzeList = () => {
    const state = getState();
    if (!state.files.length) {
      analyzeList.innerHTML = '<div class="empty-state">No uploaded files available for analysis.</div>';
      confirmAnalysisButton.disabled = true;
      return;
    }

    analyzeList.innerHTML = state.files.map((file, index) => `
      <label class="analysis-option">
        <input type="checkbox" data-analyze-file value="${index}" checked>
        <div>
          <strong>${escapeHtml(file.name)}</strong>
          <span>${escapeHtml(file.kind)} / ${formatFileSize(file.size)} / ${escapeHtml(statusMeta(file.parseStatus).label)}</span>
        </div>
      </label>
    `).join('');
    confirmAnalysisButton.disabled = false;
  };

  const normalizeFiles = (files) => Array.from(files).map(file => {
    const extension = file.name.includes('.') ? file.name.split('.').pop().toLowerCase() : 'file';
    return {
      name: file.name,
      size: file.size || 0,
      kind: extension === 'zip' ? 'zip package' : extension.toUpperCase()
    };
  });

  const openUploadModal = () => {
    uploadModal.classList.add('open');
  };

  const closeUploadModal = () => {
    uploadModal.classList.remove('open');
  };

  const openAnalyzeModal = () => {
    renderAnalyzeList();
    analyzeProgress.hidden = true;
    confirmAnalysisButton.disabled = false;
    selectAllAnalyzeFilesButton.disabled = false;
    analyzeModal.classList.add('open');
  };

  const closeAnalyzeModal = () => {
    analyzeModal.classList.remove('open');
    analyzeProgress.hidden = true;
  };

  const closeProfileModal = () => {
    profileModal.classList.remove('open');
  };

  const openInterviewModal = () => {
    const state = getState();
    const candidate = normalizeCandidate(state.candidates[activeCandidateIndex]);
    if (!candidate.name) {
      return;
    }
    interviewTitle.textContent = `${candidate.name} / Interview workflow`;
    interviewSubtitle.textContent = candidate.statusKey === 'interview_scheduled'
      ? 'Close the interview stage and record the result.'
      : 'Schedule the interview or decide the result immediately.';
    interviewTypeInput.value = candidate.interview?.type || 'HR Interview';
    interviewDateInput.value = candidate.interview?.date || '';
    interviewInterviewerInput.value = candidate.interview?.interviewer || 'Nicat Ismayilov';
    interviewOutcomeInput.value = candidate.statusKey === 'interview_scheduled' ? 'interviewed' : 'scheduled';
    interviewNoteInput.value = '';
    interviewModal.classList.add('open');
  };

  const closeInterviewModal = () => {
    interviewModal.classList.remove('open');
  };

  const openTalentModal = () => {
    const state = getState();
    const candidate = normalizeCandidate(state.candidates[activeCandidateIndex]);
    if (!candidate.name) {
      return;
    }

    const defaultCategory = candidate.talentPool?.saved
      ? candidate.talentPool.category
      : candidate.statusKey === 'interviewed'
        ? 'recommended'
        : candidate.statusKey === 'rejected'
          ? 'future_fit'
          : 'watchlist';

    talentTitle.textContent = `${candidate.name} / Talent Pool`;
    talentNoteInput.value = candidate.talentPool?.saved ? (candidate.talentPool.note || '') : '';
    talentModal.querySelectorAll('input[name="candidateTalentCategory"]').forEach(input => {
      input.checked = input.value === defaultCategory;
    });
    talentSaveButton.textContent = candidate.talentPool?.saved ? 'Update Talent Pool' : 'Save to Talent Pool';
    talentModal.classList.add('open');
  };

  const closeTalentModal = () => {
    talentModal.classList.remove('open');
  };

  const rejectReasonLabel = (value) => {
    const labels = {
      skill_mismatch: 'skill mismatch',
      salary_mismatch: 'salary mismatch',
      better_candidate_selected: 'better candidate selected',
      experience_mismatch: 'experience mismatch',
      candidate_withdrew: 'candidate withdrew',
      other: 'other reason'
    };
    return labels[value] || 'other reason';
  };

  const openRejectModal = () => {
    const state = getState();
    const candidate = normalizeCandidate(state.candidates[activeCandidateIndex]);
    if (!candidate.name) {
      return;
    }

    rejectTitle.textContent = `${candidate.name} / Reject Candidate`;
    rejectReasonInput.value = 'skill_mismatch';
    rejectNoteInput.value = '';
    rejectModal.classList.add('open');
  };

  const closeRejectModal = () => {
    rejectModal.classList.remove('open');
  };

  const openOfferModal = () => {
    const state = getState();
    const candidate = normalizeCandidate(state.candidates[activeCandidateIndex]);
    if (!candidate.name) {
      return;
    }

    offerTitle.textContent = `${candidate.name} / Offer Workflow`;
    offerOutcomeInput.value = candidate.statusKey === 'offer_pending' ? 'hired' : 'offer_pending';
    offerCompInput.value = candidate.offer?.compensation || '';
    offerNoteInput.value = candidate.offer?.note || '';
    offerModal.classList.add('open');
  };

  const closeOfferModal = () => {
    offerModal.classList.remove('open');
  };

  const appendFilesToQueue = (files) => {
    normalizeFiles(files).forEach(item => {
      const duplicate = queue.some(existing => existing.name === item.name && existing.size === item.size);
      if (!duplicate) {
        queue.push(item);
      }
    });
    renderQueue();
  };

  openButtons.forEach(button => button.addEventListener('click', openUploadModal));
  closeButtons.forEach(button => button.addEventListener('click', closeUploadModal));
  closeAnalyzeButtons.forEach(button => button.addEventListener('click', closeAnalyzeModal));
  closeProfileButtons.forEach(button => button.addEventListener('click', closeProfileModal));
  closeInterviewButtons.forEach(button => button.addEventListener('click', closeInterviewModal));
  closeTalentButtons.forEach(button => button.addEventListener('click', closeTalentModal));
  closeRejectButtons.forEach(button => button.addEventListener('click', closeRejectModal));
  closeOfferButtons.forEach(button => button.addEventListener('click', closeOfferModal));

  fileInput.addEventListener('change', event => {
    appendFilesToQueue(event.target.files);
    fileInput.value = '';
  });

  if (dropzone) {
    dropzone.addEventListener('click', () => fileInput.click());
    dropzone.addEventListener('dragover', event => {
      event.preventDefault();
      dropzone.classList.add('is-dragging');
    });
    dropzone.addEventListener('dragleave', () => {
      dropzone.classList.remove('is-dragging');
    });
    dropzone.addEventListener('drop', event => {
      event.preventDefault();
      dropzone.classList.remove('is-dragging');
      if (event.dataTransfer.files.length) {
        appendFilesToQueue(event.dataTransfer.files);
      }
    });
  }

  clearQueueButton.addEventListener('click', event => {
    event.preventDefault();
    queue = [];
    renderQueue();
  });

  uploadQueueButton.addEventListener('click', event => {
    event.preventDefault();
    const state = getState();
    const stamp = new Date().toLocaleString('en-GB');
    state.files = [
      ...queue.map(file => ({
        ...file,
        addedAt: stamp,
        parseStatus: 'pending',
        retryCount: 0
      })),
      ...state.files
    ];
    state.candidates = [];
    saveState(state);
    queue = [];
    renderQueue();
    renderUploadedFiles();
    renderCandidates();
    closeUploadModal();
  });

  analyzeButton.addEventListener('click', event => {
    event.preventDefault();
    const state = getState();
    if (!state.files.length) {
      openUploadModal();
      return;
    }
    openAnalyzeModal();
  });

  loadSampleButton.addEventListener('click', event => {
    event.preventDefault();
    queue = [
      { name: 'kamran-nasirov-cv.pdf', size: 482315, kind: 'PDF' },
      { name: 'aynur-quliyeva-cv.docx', size: 221300, kind: 'DOCX' },
      { name: 'backend-batch-march.zip', size: 3200300, kind: 'zip package' }
    ];
    renderQueue();
    openUploadModal();
  });

  uploadModal.addEventListener('click', event => {
    if (event.target === uploadModal) {
      closeUploadModal();
    }
  });

  analyzeModal.addEventListener('click', event => {
    if (event.target === analyzeModal) {
      closeAnalyzeModal();
    }
  });

  profileModal.addEventListener('click', event => {
    if (event.target === profileModal) {
      closeProfileModal();
    }
  });

  interviewModal.addEventListener('click', event => {
    if (event.target === interviewModal) {
      closeInterviewModal();
    }
  });

  talentModal.addEventListener('click', event => {
    if (event.target === talentModal) {
      closeTalentModal();
    }
  });

  rejectModal.addEventListener('click', event => {
    if (event.target === rejectModal) {
      closeRejectModal();
    }
  });

  offerModal.addEventListener('click', event => {
    if (event.target === offerModal) {
      closeOfferModal();
    }
  });

  talentAction.addEventListener('click', event => {
    event.preventDefault();
    if (activeCandidateIndex === null) {
      return;
    }
    const state = getState();
    const candidate = normalizeCandidate(state.candidates[activeCandidateIndex]);
    if (candidate.talentPool?.saved) {
      updateCandidate(activeCandidateIndex, currentCandidate => ({
        ...currentCandidate,
        history: ['Talent pool: Candidate removed from talent pool', ...currentCandidate.history],
        talentPool: {
          saved: false,
          category: '',
          note: '',
          addedAt: ''
        }
      }));

      const refreshed = normalizeCandidate(getState().candidates[activeCandidateIndex]);
      profileHistory.innerHTML = refreshed.history.map(item => `
        <div class="upload-item is-uploaded">
          <div>
            <strong>${escapeHtml(item.split(':')[0])}</strong>
            <span>${escapeHtml(item.split(':').slice(1).join(':').trim() || item)}</span>
          </div>
        </div>
      `).join('');
      talentAction.textContent = 'Save to Talent Pool';
      return;
    }

    openTalentModal();
  });

  talentSaveButton.addEventListener('click', event => {
    event.preventDefault();
    if (activeCandidateIndex === null) {
      return;
    }

    const selectedCategory = talentModal.querySelector('input[name="candidateTalentCategory"]:checked')?.value || 'watchlist';
    const note = talentNoteInput.value.trim();

    updateCandidate(activeCandidateIndex, candidate => ({
      ...candidate,
      history: [`Talent pool: Candidate saved as ${selectedCategory.replace('_', ' ')}`, ...candidate.history],
      talentPool: {
        saved: true,
        category: selectedCategory,
        note: note || 'Saved by recruiter as reusable talent.',
        addedAt: new Date().toISOString()
      }
    }));

    const refreshed = normalizeCandidate(getState().candidates[activeCandidateIndex]);
    profileHistory.innerHTML = refreshed.history.map(item => `
      <div class="upload-item is-uploaded">
        <div>
          <strong>${escapeHtml(item.split(':')[0])}</strong>
          <span>${escapeHtml(item.split(':').slice(1).join(':').trim() || item)}</span>
        </div>
      </div>
    `).join('');
    talentAction.textContent = 'Remove from Talent Pool';
    closeTalentModal();
  });

  rejectAction.addEventListener('click', event => {
    event.preventDefault();
    if (activeCandidateIndex === null) {
      return;
    }
    openRejectModal();
  });

  offerAction.addEventListener('click', event => {
    event.preventDefault();
    if (activeCandidateIndex === null) {
      return;
    }
    openOfferModal();
  });

  rejectSaveButton.addEventListener('click', event => {
    event.preventDefault();
    if (activeCandidateIndex === null) {
      return;
    }

    const reason = rejectReasonInput.value;
    const note = rejectNoteInput.value.trim();

    updateCandidate(activeCandidateIndex, candidate => ({
      ...candidate,
      statusKey: 'rejected',
      rejection: {
        reason,
        note
      },
      history: [
        `Decision: Candidate rejected due to ${rejectReasonLabel(reason)}`,
        ...(note ? [`Decision note: ${note}`] : []),
        ...candidate.history
      ]
    }));

    closeRejectModal();
    closeProfileModal();
  });

  offerSaveButton.addEventListener('click', event => {
    event.preventDefault();
    if (activeCandidateIndex === null) {
      return;
    }

    const outcome = offerOutcomeInput.value;
    const compensation = offerCompInput.value.trim();
    const note = offerNoteInput.value.trim();

    updateCandidate(activeCandidateIndex, candidate => {
      const history = [...candidate.history];

      if (outcome === 'rejected') {
        history.unshift('Decision: Candidate rejected during offer stage');
        if (note) {
          history.unshift(`Offer note: ${note}`);
        }
        return {
          ...candidate,
          statusKey: 'rejected',
          offer: {
            compensation,
            note
          },
          history
        };
      }

      if (outcome === 'hired') {
        history.unshift('Decision: Candidate accepted offer and was hired');
        if (compensation) {
          history.unshift(`Offer package: ${compensation}`);
        }
        if (note) {
          history.unshift(`Offer note: ${note}`);
        }
        return {
          ...candidate,
          statusKey: 'hired',
          offer: {
            compensation,
            note
          },
          history
        };
      }

      history.unshift('Offer: Candidate moved to offer pending');
      if (compensation) {
        history.unshift(`Offer package: ${compensation}`);
      }
      if (note) {
        history.unshift(`Offer note: ${note}`);
      }
      return {
        ...candidate,
        statusKey: 'offer_pending',
        offer: {
          compensation,
          note
        },
        history
      };
    });

    closeOfferModal();
    closeProfileModal();
  });

  interviewAction.addEventListener('click', event => {
    event.preventDefault();
    if (activeCandidateIndex === null) {
      return;
    }
    openInterviewModal();
  });

  selectAllAnalyzeFilesButton.addEventListener('click', event => {
    event.preventDefault();
    analyzeList.querySelectorAll('[data-analyze-file]').forEach(checkbox => {
      checkbox.checked = true;
    });
  });

  confirmAnalysisButton.addEventListener('click', event => {
    event.preventDefault();
    const selectedIndexes = Array.from(analyzeList.querySelectorAll('[data-analyze-file]:checked')).map(item => Number(item.value));
    if (!selectedIndexes.length) {
      return;
    }

    const state = getState();
    state.files = state.files.map((file, index) => (
      selectedIndexes.includes(index)
        ? { ...file, parseStatus: 'processing' }
        : file
    ));
    saveState(state);
    renderUploadedFiles();
    analyzeProgress.hidden = false;
    confirmAnalysisButton.disabled = true;
    selectAllAnalyzeFilesButton.disabled = true;

    setTimeout(() => {
      const latestState = getState();
      latestState.files = latestState.files.map((file, index) => {
        if (!selectedIndexes.includes(index)) {
          return file;
        }

        const shouldFail = file.name.toLowerCase().includes('fail') && Number(file.retryCount || 0) < 1;
        if (shouldFail) {
          return { ...file, retryCount: Number(file.retryCount || 0), parseStatus: 'failed' };
        }

        return {
          ...file,
          retryCount: Number(file.retryCount || 0),
          parseStatus: file.kind === 'zip package' ? 'parsed_batch' : 'parsed'
        };
      });

      const parsedFiles = latestState.files.filter((file, index) => selectedIndexes.includes(index) && ['parsed', 'parsed_batch'].includes(file.parseStatus));
      latestState.candidates = generateCandidateRows(parsedFiles);
      saveState(latestState);
      renderUploadedFiles();
      renderCandidates();
      closeAnalyzeModal();
    }, 3000);
  });

  interviewSaveButton.addEventListener('click', event => {
    event.preventDefault();
    if (activeCandidateIndex === null) {
      return;
    }

    const interviewPayload = {
      type: interviewTypeInput.value,
      date: interviewDateInput.value,
      interviewer: interviewInterviewerInput.value.trim() || 'Nicat Ismayilov',
      note: interviewNoteInput.value.trim()
    };

    const outcome = interviewOutcomeInput.value;
    updateCandidate(activeCandidateIndex, candidate => {
      const history = [...candidate.history];
      if (outcome === 'scheduled') {
        history.unshift(`Interview: ${interviewPayload.type} scheduled with ${interviewPayload.interviewer}`);
        if (interviewPayload.note) {
          history.unshift(`Interview note: ${interviewPayload.note}`);
        }
        return {
          ...candidate,
          statusKey: 'interview_scheduled',
          interview: interviewPayload,
          history
        };
      }

      if (outcome === 'rejected') {
        history.unshift(`Interview result: Candidate rejected after ${interviewPayload.type.toLowerCase()}`);
        if (interviewPayload.note) {
          history.unshift(`Interview note: ${interviewPayload.note}`);
        }
        return {
          ...candidate,
          statusKey: 'rejected',
          interview: interviewPayload,
          history
        };
      }

      history.unshift(`Interview result: ${interviewPayload.type} completed successfully`);
      if (interviewPayload.note) {
        history.unshift(`Interview note: ${interviewPayload.note}`);
      }
      return {
        ...candidate,
        statusKey: 'interviewed',
        interview: interviewPayload,
        history
      };
    });

    closeInterviewModal();
    closeProfileModal();
  });

  renderQueue();
  renderUploadedFiles();
  renderCandidates();
}

function initInterviewsPage() {
  const root = document.getElementById('interviewsDashboard');
  if (!root) {
    return;
  }

  const records = getInterviewRecords();
  const scheduled = records.filter(item => item.statusKey === 'interview_scheduled');
  const completed = records.filter(item => item.statusKey === 'interviewed');
  const decided = records.filter(item => item.statusKey === 'rejected');
  const nextInterview = scheduled[0] || null;

  const renderCard = (item) => `
    <article class="kanban-card">
      <div class="flex items-start justify-between gap-3 mb-3">
        <div>
          <div class="text-sm font-600 text-white">${escapeHtml(item.candidateName)}</div>
          <div class="text-xs text-slate-500">${escapeHtml(item.vacancyTitle)}</div>
        </div>
        <span class="badge ${item.statusKey === 'interview_scheduled' ? 'badge-cyan' : item.statusKey === 'interviewed' ? 'badge-green' : 'badge-red'}">${escapeHtml(item.stageLabel)}</span>
      </div>
      <div class="text-xs text-slate-400 space-y-2">
        <div>${escapeHtml(item.interviewType)} / ${escapeHtml(item.interviewer)}</div>
        <div>${escapeHtml(item.interviewDate || 'Date not set')}</div>
        <div>AI match ${escapeHtml(item.score)}%</div>
      </div>
      ${item.note ? `<div class="text-xs text-slate-500 mt-3">${escapeHtml(item.note)}</div>` : ''}
      <div class="text-[11px] text-slate-600 mt-3">${escapeHtml(item.candidateEmail)}</div>
    </article>
  `;

  root.innerHTML = `
    <section class="space-y-6">
      <div class="vacancy-hero">
        <div>
          <div class="eyebrow">Interview operations</div>
          <h1 class="vacancy-hero-title">Interview pipeline from candidate mock state</h1>
          <p class="vacancy-hero-copy">This screen reads the current interview decisions from candidate workflows. Schedule candidates from the profile modal in Candidates, then verify them here.</p>
        </div>
        <div class="vacancy-hero-actions">
          <a class="btn-primary" href="candidates.html">Open candidates</a>
          <a class="btn-ghost" href="vacancies.html">Back to vacancies</a>
        </div>
      </div>

      <div class="grid grid-cols-4 gap-4">
        <div class="stat-card">
          <div class="text-xs text-slate-500">Scheduled</div>
          <div class="mt-2 text-2xl font-display font-700 text-white">${scheduled.length}</div>
        </div>
        <div class="stat-card">
          <div class="text-xs text-slate-500">Completed</div>
          <div class="mt-2 text-2xl font-display font-700 text-white">${completed.length}</div>
        </div>
        <div class="stat-card">
          <div class="text-xs text-slate-500">Rejected after review</div>
          <div class="mt-2 text-2xl font-display font-700 text-white">${decided.length}</div>
        </div>
        <div class="stat-card">
          <div class="text-xs text-slate-500">Next interview</div>
          <div class="mt-2 text-sm font-600 text-white">${nextInterview ? escapeHtml(nextInterview.candidateName) : 'No interview queued'}</div>
          <div class="text-xs text-slate-500 mt-1">${nextInterview ? escapeHtml(nextInterview.interviewDate || nextInterview.vacancyTitle) : 'Schedule from candidates page'}</div>
        </div>
      </div>

      ${records.length ? `
        <div class="grid grid-cols-3 gap-4">
          <div class="kanban-col p-4">
            <div class="text-xs font-700 uppercase tracking-wider text-slate-500 mb-3 px-1">Scheduled <span class="badge badge-cyan ml-1">${scheduled.length}</span></div>
            <div class="space-y-3">
              ${scheduled.length ? scheduled.map(renderCard).join('') : '<div class="empty-state">No scheduled interviews yet.</div>'}
            </div>
          </div>
          <div class="kanban-col p-4">
            <div class="text-xs font-700 uppercase tracking-wider text-slate-500 mb-3 px-1">Completed <span class="badge badge-green ml-1">${completed.length}</span></div>
            <div class="space-y-3">
              ${completed.length ? completed.map(renderCard).join('') : '<div class="empty-state">No completed interviews yet.</div>'}
            </div>
          </div>
          <div class="kanban-col p-4">
            <div class="text-xs font-700 uppercase tracking-wider text-slate-500 mb-3 px-1">Decision Logged <span class="badge badge-red ml-1">${decided.length}</span></div>
            <div class="space-y-3">
              ${decided.length ? decided.map(renderCard).join('') : '<div class="empty-state">No rejected interview outcomes yet.</div>'}
            </div>
          </div>
        </div>
      ` : `
        <div class="card">
          <div class="empty-state">No interview data yet. Go to candidates, then use "Move to interview" to create a record.</div>
        </div>
      `}
    </section>
  `;
}

function initTalentPage() {
  const root = document.getElementById('talentDashboard');
  const reuseModal = document.getElementById('talentReuseModal');
  const reuseTitle = document.getElementById('talentReuseTitle');
  const reuseVacancyInput = document.getElementById('talentReuseVacancy');
  const reuseNoteInput = document.getElementById('talentReuseNote');
  const reuseSaveButton = document.getElementById('talentReuseSave');
  const closeReuseButtons = document.querySelectorAll('[data-action="close-talent-reuse-modal"]');

  if (!root || !reuseModal || !reuseTitle || !reuseVacancyInput || !reuseNoteInput || !reuseSaveButton) {
    return;
  }

  let activeTalentRecord = null;
  const records = getTalentPoolRecords();
  const recommended = records.filter(item => item.talentPool?.category === 'recommended');
  const watchlist = records.filter(item => item.talentPool?.category === 'watchlist');
  const futureFit = records.filter(item => item.talentPool?.category === 'future_fit');

  const categoryMeta = {
    recommended: { label: 'Recommended', badge: 'badge-green' },
    watchlist: { label: 'Watchlist', badge: 'badge-cyan' },
    future_fit: { label: 'Future fit', badge: 'badge-yellow' }
  };

  const openReuseModal = (record) => {
    activeTalentRecord = record;
    reuseTitle.textContent = `${record.name} / Add to Vacancy`;
    reuseNoteInput.value = '';
    const vacancies = getVacancyStore();
    reuseVacancyInput.innerHTML = vacancies.map(item => `
      <option value="${escapeHtml(item.id)}">${escapeHtml(item.title)} / ${escapeHtml(item.department)}</option>
    `).join('');
    reuseModal.classList.add('open');
  };

  const closeReuseModal = () => {
    reuseModal.classList.remove('open');
    activeTalentRecord = null;
  };

  const renderCard = item => {
    const meta = categoryMeta[item.talentPool?.category] || categoryMeta.watchlist;
    const initials = item.name.split(' ').map(part => part.charAt(0)).join('').slice(0, 2).toUpperCase();
    const primaryNote = item.talentPool?.note || item.history[0] || 'Saved for future use.';
    return `
      <article class="card card-hover">
        <div class="flex items-start gap-3 mb-4">
          <div class="avatar bg-gradient-to-br from-brand-600 to-accent text-white w-12 h-12 text-sm flex-shrink-0">${escapeHtml(initials)}</div>
          <div class="flex-1">
            <div class="font-display font-700 text-white">${escapeHtml(item.name)}</div>
            <div class="text-xs text-slate-500">${escapeHtml(item.vacancyTitle)} / ${escapeHtml(item.experience)}</div>
            <span class="badge ${meta.badge} mt-1">${escapeHtml(meta.label)}</span>
          </div>
        </div>
        <div class="flex flex-wrap gap-1.5 mb-3">
          ${item.skills.slice(0, 4).map(skill => `<span class="badge badge-blue">${escapeHtml(skill)}</span>`).join('')}
        </div>
        <div class="text-xs text-slate-500 mb-3 flex items-center gap-4">
          <span>AI match ${escapeHtml(item.score)}%</span>
          <span>${escapeHtml(item.source)}</span>
        </div>
        <div class="p-3 rounded-lg bg-surface-700 border border-brand-900/30 text-xs text-slate-400 mb-3 italic">
          "${escapeHtml(primaryNote)}"
        </div>
        <div class="text-xs text-slate-500 mb-4">${escapeHtml(item.email)}</div>
        <div class="flex gap-2">
          <button class="btn-primary text-xs py-1.5 flex-1 text-center" type="button" data-action="reuse-talent" data-vacancy-id="${escapeHtml(item.vacancyId)}" data-candidate-index="${item.candidateIndex}">Add to vacancy</button>
          <a class="btn-ghost text-xs py-1.5 px-3" href="candidates.html?vacancy=${encodeURIComponent(item.vacancyId)}">Profile</a>
        </div>
      </article>
    `;
  };

  root.innerHTML = `
    <section class="space-y-6">
      <div class="vacancy-hero">
        <div>
          <div class="eyebrow">Reusable candidates</div>
          <h1 class="vacancy-hero-title">Talent Pool built from recruiter decisions</h1>
          <p class="vacancy-hero-copy">Candidates only appear here after the recruiter explicitly saves them from the candidate profile. This keeps the pool intentional instead of turning every rejected profile into clutter.</p>
        </div>
        <div class="vacancy-hero-actions">
          <a class="btn-primary" href="candidates.html">Open candidates</a>
          <a class="btn-ghost" href="interviews.html">Open interviews</a>
        </div>
      </div>

      <div class="grid grid-cols-4 gap-4">
        <div class="stat-card">
          <div class="text-xs text-slate-500">Total in pool</div>
          <div class="mt-2 text-2xl font-display font-700 text-white">${records.length}</div>
        </div>
        <div class="stat-card">
          <div class="text-xs text-slate-500">Recommended</div>
          <div class="mt-2 text-2xl font-display font-700 text-white">${recommended.length}</div>
        </div>
        <div class="stat-card">
          <div class="text-xs text-slate-500">Watchlist</div>
          <div class="mt-2 text-2xl font-display font-700 text-white">${watchlist.length}</div>
        </div>
        <div class="stat-card">
          <div class="text-xs text-slate-500">Future fit</div>
          <div class="mt-2 text-2xl font-display font-700 text-white">${futureFit.length}</div>
        </div>
      </div>

      ${records.length ? `
        <div class="grid grid-cols-3 gap-4">
          ${records.map(renderCard).join('')}
        </div>
      ` : `
        <div class="card">
          <div class="empty-state">No talent pool candidate yet. Open a candidate profile and click "Save to Talent Pool".</div>
        </div>
      `}
    </section>
  `;

  root.querySelectorAll('[data-action="reuse-talent"]').forEach(button => {
    button.addEventListener('click', event => {
      const vacancyId = event.currentTarget.dataset.vacancyId;
      const candidateIndex = Number(event.currentTarget.dataset.candidateIndex);
      const record = records.find(item => item.vacancyId === vacancyId && item.candidateIndex === candidateIndex);
      if (!record) {
        return;
      }
      openReuseModal(record);
    });
  });

  closeReuseButtons.forEach(button => button.addEventListener('click', closeReuseModal));

  reuseModal.addEventListener('click', event => {
    if (event.target === reuseModal) {
      closeReuseModal();
    }
  });

  reuseSaveButton.addEventListener('click', event => {
    event.preventDefault();
    if (!activeTalentRecord) {
      return;
    }

    const targetVacancyId = reuseVacancyInput.value;
    const recruiterNote = reuseNoteInput.value.trim();
    const cvStore = getCvStore();
    const sourceState = cvStore[activeTalentRecord.vacancyId];
    const sourceCandidate = sourceState?.candidates?.[activeTalentRecord.candidateIndex];
    if (!sourceCandidate) {
      closeReuseModal();
      return;
    }

    if (!cvStore[targetVacancyId]) {
      cvStore[targetVacancyId] = { files: [], candidates: [] };
    }

    const targetCandidates = Array.isArray(cvStore[targetVacancyId].candidates) ? cvStore[targetVacancyId].candidates : [];
    const duplicate = targetCandidates.some(candidate =>
      String(candidate?.email || '').toLowerCase() === String(sourceCandidate?.email || '').toLowerCase()
      && String(candidate?.name || '').toLowerCase() === String(sourceCandidate?.name || '').toLowerCase()
    );

    if (!duplicate) {
      targetCandidates.unshift({
        ...sourceCandidate,
        statusKey: 'ai_analyzed',
        source: 'Talent Pool',
        history: [
          `Talent pool: Added to vacancy from talent pool`,
          ...(recruiterNote ? [`Recruiter note: ${recruiterNote}`] : []),
          ...(Array.isArray(sourceCandidate.history) ? sourceCandidate.history : [])
        ]
      });
      cvStore[targetVacancyId].candidates = targetCandidates;
      saveCvStore(cvStore);
    }

    closeReuseModal();
    window.location.href = `candidates.html?vacancy=${encodeURIComponent(targetVacancyId)}`;
  });
}
