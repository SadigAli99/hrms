# Technical Recruitment Flow

## Scope

This document describes the current recruitment UI structure in this project and how backend integration should connect to it.

The recruitment scope currently includes:

- vacancy listing
- vacancy create/edit form
- candidate intake and AI analysis UI
- interview pipeline
- talent pool
- offer and hiring decision flow

The recruitment pages are now static HTML pages with page-specific JavaScript.
They no longer depend on mock `localStorage` rendering.

## Active Files

### HTML pages

- `vacancies.html`
- `vacancy-create.html`
- `candidates.html`
- `interviews.html`
- `talent.html`

### Shared and page scripts

- `assets/js/ui-shell.js`
- `assets/js/vacancies-page.js`
- `assets/js/vacancy-form-page.js`
- `assets/js/candidates-page.js`
- `assets/js/interviews-page.js`
- `assets/js/talent-page.js`

### Styles

- `assets/css/hr-platform.css`
- `assets/css/recruitment-pages.css`

## Current Architecture

### UI shell

`assets/js/ui-shell.js` only handles shared shell behavior:

- theme toggle
- theme persistence in `localStorage`

This is the only remaining `localStorage` usage in the recruitment pages.

### Recruitment pages

Each recruitment page now has:

- static HTML markup
- reusable CSS classes
- minimal page-specific interaction JS

No recruitment page currently loads `assets/js/hr-platform.js`.

## Page Responsibilities

### 1. Vacancies

File:

- `vacancies.html`

Script:

- `assets/js/vacancies-page.js`

Purpose:

- show vacancy cards
- filter by search, department and status
- route user to candidate pipeline or vacancy form

Backend expectation:

- render vacancy cards server-side
- fill `data-vacancy-card` attributes for filtering:
  - `data-title`
  - `data-department`
  - `data-location`
  - `data-status`

Expected card actions:

- open candidates page for a vacancy
- open edit page for a vacancy
- delete action can later be turned into a backend form or endpoint call

### 2. Vacancy Create / Edit

File:

- `vacancy-create.html`

Script:

- `assets/js/vacancy-form-page.js`

Purpose:

- collect vacancy fields
- allow structured requirements as tag list
- serialize tag list into hidden field

Important fields:

- `title`
- `department`
- `employment_type`
- `work_mode`
- `seniority_level`
- `status`
- `location`
- `close_date`
- `min_salary`
- `max_salary`
- `description`
- `requirements_text`
- `vacancy_requirements_payload`

`vacancy_requirements_payload` is a hidden input containing JSON like:

```json
[
  { "label": "Laravel", "type": "skill", "required": true },
  { "label": "Docker", "type": "tool", "required": false }
]
```

Backend options:

- accept the JSON field directly
- or ignore it and rebuild requirement rows from normal form arrays later

### 3. Candidates

File:

- `candidates.html`

Script:

- `assets/js/candidates-page.js`

Purpose:

- upload CV files
- show upload queue
- show uploaded files
- open AI analysis modal
- open candidate profile modal
- open interview / reject / offer / talent modals

Current frontend behavior:

- file queue works in browser memory only
- uploaded list works in browser memory only
- analyze modal selection works in browser memory only
- modals open and close correctly

Important note:

This page is backend-ready structurally, but upload and analysis data are not yet submitted to backend automatically.
When backend is connected, this page should become the main active recruitment workflow page.

### 4. Interviews

File:

- `interviews.html`

Script:

- `assets/js/interviews-page.js`

Purpose:

- show grouped interview pipeline columns
- allow simple client-side search over static cards

Expected backend rendering:

- scheduled interviews
- completed interviews
- decision logged / rejected after interview

Each card should expose:

- candidate name
- role or vacancy
- stage

through `data-interview-card` attributes for search.

### 5. Talent Pool

File:

- `talent.html`

Script:

- `assets/js/talent-page.js`

Purpose:

- show reusable candidates outside active vacancy process
- search/filter talent entries
- open "Add to vacancy" modal

Important business rule:

Talent pool candidates are not directly interviewed from the talent page.
The correct flow is:

1. candidate exists in talent pool
2. recruiter adds candidate to a vacancy
3. backend creates candidate application for that vacancy
4. candidate appears in `candidates.html`
5. only then interview flow starts

## Business Flow

### Main recruitment flow

1. Recruiter creates vacancy
2. Recruiter uploads CV files for that vacancy
3. AI analysis runs against uploaded CVs
4. Candidate list is generated for that vacancy
5. Recruiter reviews candidate profile
6. Recruiter chooses one of:
   - move to interview
   - reject
   - save to talent pool
7. If interview succeeds:
   - move to offer pending
   - then mark as hired or rejected

### Talent pool flow

1. Recruiter saves a candidate to talent pool
2. Talent candidate stays outside active vacancy flow
3. Recruiter later adds talent candidate to another vacancy
4. Backend creates new candidate application
5. Candidate enters active vacancy process again

## Recommended Backend Data Model

These are the key entities already assumed by the UI design:

- `vacancies`
- `vacancy_requirements`
- `candidates`
- `candidate_cv_files`
- `candidate_profiles`
- `candidate_applications`
- `ai_analyses`
- `interviews`
- `candidate_history_notes`

### Important distinction

- `candidate` = person
- `candidate_application` = that person's process for one specific vacancy

This distinction is required for:

- repeat applications
- interview history
- talent pool reuse
- rejection reason tracking

## Status Model

### Candidate application statuses

Recommended statuses for backend:

- `ai_analyzed`
- `interview_scheduled`
- `interviewed`
- `offer_pending`
- `hired`
- `rejected`

### Reject reasons

Recommended reject reason values:

- `skill_mismatch`
- `salary_mismatch`
- `better_candidate_selected`
- `experience_mismatch`
- `candidate_withdrew`
- `other`

### Talent pool categories

Recommended talent pool category values:

- `recommended`
- `watchlist`
- `future_fit`

## Backend Integration Notes

### Vacancies page

Replace static cards with server loop.

Recommended backend data per card:

- id
- title
- department
- work_mode
- employment_type
- seniority_level
- location
- close_date
- status
- applicants_count
- interviews_count
- requirements_preview

### Vacancy form

Server should:

- prefill field values on edit
- accept `POST` or `PUT`
- parse `vacancy_requirements_payload`

### Candidates page

Server should eventually handle:

- file upload endpoint
- AI analysis trigger endpoint
- candidate list query by vacancy
- candidate status updates
- reject reason save
- offer stage updates
- talent pool save

### Interviews page

Server should group cards by stage:

- scheduled
- completed
- decision_logged

### Talent page

Server should provide:

- candidate identity
- primary role label
- skills preview
- source vacancy
- talent category
- add-to-vacancy action

## Current Frontend Limitations

These parts are still placeholder-level and should be connected to backend next:

- file upload is in-memory only
- AI analysis is UI-only
- candidate rows are still mostly demo content
- interview, reject, offer and talent actions currently open modals only
- form submit actions and modal saves are not wired to real endpoints

## Recommended Next Backend Order

1. Connect vacancy form create/update
2. Connect vacancy listing data
3. Connect candidate list by vacancy
4. Connect CV upload endpoint
5. Connect AI analysis trigger
6. Connect candidate status transitions
7. Connect talent pool add/remove
8. Connect add-to-vacancy from talent pool
9. Connect interviews board
10. Connect offer and hired decision save

## Summary

The recruitment UI is now in a backend-friendly state:

- no recruitment rendering depends on mock `localStorage`
- HTML structures are explicit
- JS is split per page
- backend can now replace static rows with real server data incrementally

