@extends('layouts.master')

@section('title', 'Namizedler')

@section('content')
    @php
        $applicationCount = method_exists($applications, 'total') ? $applications->total() : $applications->count();
        $analyzedCount = $applications->filter(fn($application) => $application->analyses->isNotEmpty())->count();
        $waitingCount = max($applicationCount - $analyzedCount, 0);
    @endphp

    <div class="page-block">
        <div class="flex items-center justify-between mb-6">
            <p class="text-slate-400 text-sm" id="candidatePageStatus">
                {{ $vacancy->title }} ·
                <span class="text-brand-400 font-600" id="candidateCount">{{ $applicationCount }}</span>
                candidate ·
                {{ $waitingCount > 0 ? $waitingCount . ' waiting for AI analysis' : 'all analyzed' }}
            </p>
            <div class="flex gap-3">
                <button class="btn-ghost flex items-center gap-2" type="button" data-action="open-upload-modal">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    CV Yukle
                </button>
                <button class="btn-primary flex items-center gap-2" type="button" id="runCandidateAnalysis">
                    <span class="ai-pulse">*</span> AI ile Analiz Et
                </button>
            </div>
        </div>

        <div class="mb-5 p-4 rounded-xl border border-cyan-500/25 bg-cyan-500/5 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-cyan-500/15 flex items-center justify-center flex-shrink-0">
                <span class="text-xl">*</span>
            </div>
            <div class="flex-1">
                <div class="text-sm font-600 text-cyan-300">AI suggestion</div>
                <div class="text-xs text-slate-500 mt-0.5" id="candidateAiHint">
                    @if ($analyzedCount > 0)
                        {{ $analyzedCount }} candidate analyzed for this vacancy.
                    @else
                        No AI result yet. Upload files first.
                    @endif
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="vacancy-section-header">
                <div>
                    <div class="text-base font-display font-700 text-white">Uploaded CV files</div>
                    <div class="text-xs text-slate-500">These files are ready for AI analysis. You can remove any of them
                        before selecting for analysis.</div>
                </div>
            </div>
            <div class="upload-list" id="uploadedCvList" data-server-rendered="true">
                @forelse ($cv_files as $cvFile)
                    @php
                        $parseStatus = $cvFile?->parse_status?->value ?? 'pending';
                    @endphp
                    <div class="upload-item is-uploaded" data-cv-item data-cv-file-id="{{ $cvFile->id }}">
                        <div class="upload-item-content">
                            <strong>{{ $cvFile?->original_name ?? 'CV file' }}</strong>
                            <span class="upload-item-meta">
                                {{ $cvFile?->file_type ?? 'FILE' }} /
                                {{ $cvFile?->candidate?->full_name ?? 'Candidate not parsed yet' }}
                            </span>
                            <div class="mt-2" data-cv-status-wrap>
                                <span class="status-pill {{ $parseStatus }}">
                                    {{ str_replace('_', ' ', ucfirst($parseStatus)) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="text-right text-xs text-slate-500">
                                <div>{{ $cvFile?->candidate_id ? 'linked_candidate' : 'pending_parse' }}</div>
                                <div class="mt-1">{{ optional($cvFile?->created_at)->format('d.m.Y H:i') }}</div>
                            </div>
                            <div class="table-actions">
                                <button class="icon-action-btn danger tooltip" type="button" title="Delete CV"
                                    data-tip="Delete CV" aria-label="Delete CV"
                                    data-open-delete-cv-modal
                                    data-cv-file-id="{{ $cvFile->id }}"
                                    data-cv-file-name="{{ $cvFile->original_name }}"
                                    data-delete-url="{{ route('candidate.delete-cv', $cvFile->id) }}">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-3h4m-5 0a1 1 0 00-.894.553L7 7h10l-1.106-2.447A1 1 0 0015 4m-5 0h5" />
                                    </svg>
                                </button>
                                @if ($parseStatus === 'failed')
                                    <button class="icon-action-btn tooltip" type="button" title="Retry parse"
                                        data-tip="Retry parse" data-open-retry-parse-modal
                                        data-cv-file-id="{{ $cvFile->id }}"
                                        data-cv-file-name="{{ $cvFile->original_name }}"
                                        data-retry-url="{{ \Illuminate\Support\Facades\Route::has('candidate.retry-parse') ? route('candidate.retry-parse') : '' }}"
                                        aria-label="Retry parse">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0A8.003 8.003 0 015.03 15m14.389 0H15" />
                                        </svg>
                                    </button>
                                @endif
                                @if (in_array($parseStatus, ['pending', 'processing'], true))
                                    <button class="icon-action-btn tooltip" type="button" title="Analyze this file"
                                        data-tip="Analyze this file" data-analyze-cv-trigger
                                        data-cv-file-id="{{ $cvFile->id }}"
                                        data-analyze-url="{{ route('candidate.analyze-cv') }}"
                                        aria-label="Analyze this file">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.75 3v2.25M14.25 3v2.25M4.5 9.75h15M6.75 6.75h10.5A2.25 2.25 0 0119.5 9v8.25a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 17.25V9A2.25 2.25 0 016.75 6.75zm4.5 5.25l1.5 1.5 3-3" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">No uploaded CV files yet.</div>
                @endforelse
            </div>
        </div>

        <div class="card">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>AI Match</th>
                        <th>Skills</th>
                        <th>Experience</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="candidateResultsBody">
                    @forelse ($applications as $application)
                        @php
                            $candidate = $application->candidate;
                            $analysis = $application->analyses->sortByDesc('id')->first();
                            $matchedSkills = collect($analysis?->matched_skills_json ?? []);
                            $status = $application->status?->value ?? 'new';
                            $score = (float) ($analysis?->overall_score ?? 0);
                            $scorePercent = max(0, min(100, round($score)));
                            $circumference = 2 * pi() * 20;
                            $scoreOffset = $circumference - (($scorePercent / 100) * $circumference);
                            $experienceLabel = $candidate?->total_experience_years
                                ? rtrim(rtrim((string) $candidate->total_experience_years, '0'), '.') . ' il'
                                : 'Tecrube yoxdur';
                            $allSkills = $matchedSkills->implode(', ');
                            $initials = collect(explode(' ', trim((string) ($candidate?->full_name ?? 'NA'))))
                                ->filter()
                                ->take(2)
                                ->map(fn($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                                ->implode('');

                            if ($scorePercent >= 85) {
                                $scoreStroke = '#3178f6';
                                $avatarGradient = 'from-brand-600 to-brand-400';
                            } elseif ($scorePercent >= 70) {
                                $scoreStroke = '#8b5cf6';
                                $avatarGradient = 'from-violet-600 to-violet-400';
                            } elseif ($scorePercent >= 50) {
                                $scoreStroke = '#10b981';
                                $avatarGradient = 'from-green-600 to-green-400';
                            } else {
                                $scoreStroke = '#f59e0b';
                                $avatarGradient = 'from-amber-600 to-amber-400';
                            }

                            $statusMap = [
                                'new' => ['label' => 'Yeni', 'class' => 'badge-blue'],
                                'ai_analyzed' => ['label' => 'CV Kechdi', 'class' => 'badge-yellow'],
                                'shortlisted' => ['label' => 'Talent Pool', 'class' => 'badge-green'],
                                'interview_scheduled' => ['label' => 'Musahibe', 'class' => 'badge-cyan'],
                                'interviewed' => ['label' => 'Musahibe', 'class' => 'badge-cyan'],
                                'offer_pending' => ['label' => 'Teklif', 'class' => 'badge-purple'],
                                'hired' => ['label' => 'Ise qebul', 'class' => 'badge-green'],
                                'rejected' => ['label' => 'Redd', 'class' => 'badge-red'],
                                'on_hold' => ['label' => 'Gozlemede', 'class' => 'badge-yellow'],
                            ];
                            $statusMeta = $statusMap[$status] ?? ['label' => str_replace('_', ' ', ucfirst($status)), 'class' => 'badge-cyan'];
                            $missingSkills = collect($analysis?->missing_skills_json ?? []);
                            $requirementMatchStatusMap = [
                                'matched' => ['status' => 'matched', 'label' => 'Uyğundur'],
                                'partial' => ['status' => 'partial', 'label' => 'Qismən'],
                                'missing' => ['status' => 'missing', 'label' => 'Çatışmır'],
                                'unclear' => ['status' => 'review', 'label' => 'Yoxlanmalı'],
                            ];
                            $vacancyRequirementComparisons = collect($analysis?->requirement_matches_json ?? [])->map(function ($item) use ($requirementMatchStatusMap) {
                                $matchKey = strtolower((string) ($item['match_status'] ?? 'unclear'));
                                $statusMeta = $requirementMatchStatusMap[$matchKey] ?? $requirementMatchStatusMap['unclear'];

                                return [
                                    'label' => $item['requirement'] ?? 'Requirement',
                                    'status' => $statusMeta['status'],
                                    'statusLabel' => $statusMeta['label'],
                                    'confidence' => isset($item['confidence']) ? (int) $item['confidence'] : null,
                                    'notes' => $item['notes'] ?? null,
                                    'weightApplied' => $item['weight_applied'] ?? null,
                                ];
                            })->values();
                            if ($vacancyRequirementComparisons->isEmpty()) {
                                $vacancyRequirementComparisons = collect($vacancy->requirements ?? [])->map(function ($requirement) use ($matchedSkills, $missingSkills) {
                                $name = trim((string) ($requirement->requirement_name ?? 'Requirement'));
                                $value = trim((string) ($requirement->requirement_value ?? ''));
                                $display = $value !== '' ? $name . ': ' . $value : $name;
                                $needle = mb_strtolower($name);

                                $isMatched = $matchedSkills->contains(function ($item) use ($needle) {
                                    $text = mb_strtolower((string) $item);
                                    return $text === $needle || str_contains($text, $needle) || str_contains($needle, $text);
                                });

                                $isMissing = $missingSkills->contains(function ($item) use ($needle) {
                                    $text = mb_strtolower((string) $item);
                                    return $text === $needle || str_contains($text, $needle) || str_contains($needle, $text);
                                });

                                $status = $isMatched ? 'matched' : ($isMissing ? 'missing' : 'review');
                                $label = $isMatched ? 'Uyğundur' : ($isMissing ? 'Çatışmır' : 'Yoxlanmalı');

                                    return [
                                        'label' => $display,
                                        'status' => $status,
                                        'statusLabel' => $label,
                                        'confidence' => null,
                                        'notes' => null,
                                        'weightApplied' => null,
                                    ];
                                })->values();
                            }
                            $criticalMissing = collect($analysis?->critical_missing_json ?? [])
                                ->map(fn($item) => is_array($item) ? ($item['requirement'] ?? $item['label'] ?? $item['name'] ?? null) : $item)
                                ->filter()
                                ->values()
                                ->all();
                            $riskFlags = collect($analysis?->risk_flags_json ?? [])
                                ->map(fn($item) => is_array($item) ? ($item['label'] ?? $item['risk'] ?? $item['message'] ?? null) : $item)
                                ->filter()
                                ->values()
                                ->all();
                            $analysisNotes = is_array($analysis?->notes_json) ? $analysis->notes_json : [];
                            $profilePayload = [
                                'name' => $candidate?->full_name ?: 'Namized yoxdur',
                                'meta' => $candidate?->email ?: ($candidate?->phone ?: 'Elaqe melumati yoxdur'),
                                'score' => $analysis ? $scorePercent . '%' : 'Not analyzed',
                                'scoreValue' => $scorePercent,
                                'summary' => $candidate?->current_title ?: ($analysis?->pros_text ?: 'AI summary yoxdur'),
                                'skillsScore' => $analysis?->skills_score !== null ? round((float) $analysis->skills_score) . '%' : '0%',
                                'experienceScore' => $analysis?->experience_score !== null ? round((float) $analysis->experience_score) . '%' : '0%',
                                'educationScore' => $analysis?->education_score !== null ? round((float) $analysis->education_score) . '%' : '0%',
                                'languageScore' => $analysis?->languages_score !== null ? round((float) $analysis->languages_score) . '%' : '0%',
                                'statusLabel' => $statusMeta['label'],
                                'statusClass' => $statusMeta['class'],
                                'experience' => $experienceLabel,
                                'currentTitle' => $candidate?->current_title ?: 'Title yoxdur',
                                'seniorityFit' => $analysis?->seniority_fit,
                                'salaryFit' => $analysis?->salary_fit,
                                'expectedSalary' => $analysisNotes['expected_salary'] ?? null,
                                'vacancySalary' => collect([$analysisNotes['vacancy_min_salary'] ?? null, $analysisNotes['vacancy_max_salary'] ?? null])
                                    ->filter(fn($value) => $value !== null && $value !== '')
                                    ->implode(' - '),
                                'scoreReasoning' => $analysisNotes['overall_score_reasoning'] ?? null,
                                'candidateId' => $candidate?->id,
                                'applicationId' => $application?->id,
                                'vacancyId' => $vacancy?->id,
                                'talentSaveUrl' => \Illuminate\Support\Facades\Route::has('candidate.talent-pool')
                                    ? route('candidate.talent-pool', $application->id)
                                    : '',
                                'scoreAdjustments' => collect($analysisNotes['score_adjustment_notes_json'] ?? [])
                                    ->map(function ($item) {
                                        if (!is_array($item)) {
                                            return [
                                                'factor' => 'adjustment',
                                                'impact' => 0,
                                                'reason' => (string) $item,
                                            ];
                                        }

                                        return [
                                            'factor' => $item['factor'] ?? 'adjustment',
                                            'impact' => (int) ($item['impact'] ?? 0),
                                            'reason' => $item['reason'] ?? null,
                                        ];
                                    })
                                    ->values()
                                    ->all(),
                                'skills' => $matchedSkills->values()->all(),
                                'gaps' => collect($analysis?->missing_skills_json ?? [])->values()->all(),
                                'criticalMissing' => $criticalMissing,
                                'riskFlags' => $riskFlags,
                                'requirements' => $vacancyRequirementComparisons->all(),
                                'history' => array_values(array_filter([
                                    optional($application?->applied_at)->format('d.m.Y H:i')
                                        ? 'Muraciet tarixi: ' . optional($application->applied_at)->format('d.m.Y H:i')
                                        : null,
                                    optional($analysis?->analyzed_at)->format('d.m.Y H:i')
                                        ? 'AI analiz tarixi: ' . optional($analysis->analyzed_at)->format('d.m.Y H:i')
                                        : null,
                                    ($analysisNotes['expected_salary'] ?? null) !== null
                                        ? 'Gözlənilən maaş: ' . $analysisNotes['expected_salary']
                                        : null,
                                    $analysis?->pros_text ? 'Qeydlər: ' . $analysis->pros_text : null,
                                    $analysis?->cons_text ? 'Yoxlanılmalı hissələr: ' . $analysis->cons_text : null,
                                ])),
                            ];
                        @endphp
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar bg-gradient-to-br {{ $avatarGradient }} text-white text-xs">{{ $initials ?: 'NA' }}</div>
                                    <div>
                                        <div class="font-600 text-white text-sm">{{ $candidate?->full_name ?: 'Namized yoxdur' }}</div>
                                        <div class="text-xs text-slate-500">{{ $candidate?->email ?: ($candidate?->phone ?: 'Elaqe melumati yoxdur') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if ($analysis)
                                    <div class="flex items-center gap-2 tooltip" data-tip="AI score: {{ $scorePercent }}%">
                                        <div class="score-ring" style="width:48px;height:48px">
                                            <svg width="48" height="48" viewBox="0 0 48 48">
                                                <circle cx="24" cy="24" r="20" fill="none" stroke="#1c2640" stroke-width="4"/>
                                                <circle cx="24" cy="24" r="20" fill="none" stroke="{{ $scoreStroke }}" stroke-width="4"
                                                    stroke-dasharray="{{ number_format($circumference, 1, '.', '') }}"
                                                    stroke-dashoffset="{{ number_format($scoreOffset, 1, '.', '') }}"
                                                    stroke-linecap="round"/>
                                            </svg>
                                            <div class="score-text"><span class="text-xs font-800 text-white">{{ $scorePercent }}%</span></div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-xs text-slate-500">Not analyzed</div>
                                @endif
                            </td>
                            <td>
                                @if ($matchedSkills->isNotEmpty())
                                    <div class="flex flex-wrap gap-1 tooltip" data-tip="{{ $allSkills }}">
                                        @foreach ($matchedSkills->take(2) as $skill)
                                            <span class="badge {{ $scorePercent >= 70 ? 'badge-blue' : 'badge-green' }}">{{ $skill }}</span>
                                        @endforeach
                                        @if ($matchedSkills->count() > 2)
                                            <span class="badge badge-cyan">+{{ $matchedSkills->count() - 2 }}</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-xs text-slate-500">Bacariq melumati yoxdur</div>
                                @endif
                            </td>
                            <td class="text-slate-300">
                                <div>{{ $experienceLabel }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ $candidate?->current_title ?: 'Title yoxdur' }}</div>
                            </td>
                            <td><span class="badge {{ $statusMeta['class'] }}">{{ $statusMeta['label'] }}</span></td>
                            <td>
                                <button class="{{ $status === 'rejected' ? 'btn-ghost' : 'btn-primary' }} text-xs py-1 px-3" type="button" data-open-profile data-profile='@json($profilePayload)'>
                                    Profil
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">No analyzed candidates yet. Upload CV files and click "AI ile Analiz Et".</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($applications instanceof \Illuminate\Contracts\Pagination\Paginator && $applications->hasPages())
                <div class="mt-4">
                    {{ $applications->links('vendor.pagination.hrms') }}
                </div>
            @endif
        </div>
    </div>

    @include('pages.recruitment.candidates.partials.upload-modal')
    @include('pages.recruitment.candidates.partials.analyze-modal')
    @include('pages.recruitment.candidates.partials.delete-cv-modal')
    @include('pages.recruitment.candidates.partials.profile-modal')
    @include('pages.recruitment.candidates.partials.interview-modal')
    @include('pages.recruitment.candidates.partials.talent-modal')
    @include('pages.recruitment.candidates.partials.reject-modal')
    @include('pages.recruitment.candidates.partials.offer-modal')
    @include('pages.recruitment.candidates.partials.retry-parse-modal')
@endsection

@push('js')
    <script src="{{ asset('assets/js/candidates-page.js') }}"></script>
@endpush
