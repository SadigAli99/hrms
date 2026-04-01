@extends('layouts.master')

@section('title', 'Vakansiya yenilə')

@section('content')
    <div class="page-block">
        @php
            $closedDateValue = old('closed_at', $vacancy->closed_at->format('Y-m-d'));
            $closedDateDisplay = $closedDateValue;
            $oldRequirements = old('vacancy_requirements', $vacancy->requirements);

            try {
                $closedDateDisplay = str_contains($closedDateValue, '-')
                    ? \Illuminate\Support\Carbon::parse($closedDateValue)->format('d-m-Y')
                    : $closedDateValue;
            } catch (\Throwable $e) {
                $closedDateDisplay = $closedDateValue;
            }
        @endphp

        @if (session('success_message'))
            <div class="card border border-green-500/20 bg-green-500/10 mb-6">
                <div class="text-sm font-semibold text-green-300">{{ session('success_message') }}</div>
            </div>
        @endif

        @if (session('error_message'))
            <div class="card border border-red-500/20 bg-red-500/10 mb-6">
                <div class="text-sm font-semibold text-red-300">{{ session('error_message') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="card border border-amber-500/20 bg-amber-500/10 mb-6">
                <div class="text-sm font-semibold text-amber-300">Zehmet olmasa xetalari duzeldin.</div>
            </div>
        @endif

        <form id="vacancyForm" class="space-y-6" method="post" action="{{ route('vacancy.update', $vacancy->id) }}">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label" for="vacancyTitle">Title</label>
                        <input class="input" id="vacancyTitle" name="title" value="{{ old('title', $vacancy->title) }}">
                        @error('title')
                            <div class="mt-2 text-xs text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="label" for="vacancyDepartmentInput">Departament</label>
                        <select class="input" id="vacancyDepartmentInput" name="department_id">
                            <option value="">Secin</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ (int) old('department_id', $vacancy->department_id) === $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="mt-2 text-xs text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="label" for="vacancyEmploymentType">Is saati</label>
                        <select class="input" id="vacancyEmploymentType" name="employment_type">
                            @foreach ($employment_types as $value => $employment_type)
                                <option value="{{ $value }}"
                                    {{ (string) old('employment_type', $vacancy->employment_type->value) === $value ? 'selected' : '' }}>
                                    {{ $employment_type }}
                                </option>
                            @endforeach
                        </select>
                        @error('employment_type')
                            <div class="mt-2 text-xs text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="label" for="vacancyWorkMode">Work mode</label>
                        <select class="input" id="vacancyWorkMode" name="work_mode">
                            @foreach ($work_modes as $value => $work_mode)
                                <option value="{{ $value }}"
                                    {{ (string) old('work_mode', $vacancy->work_mode->value) === $value ? 'selected' : '' }}>
                                    {{ $work_mode }}
                                </option>
                            @endforeach
                        </select>
                        @error('work_mode')
                            <div class="mt-2 text-xs text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="label" for="vacancySeniority">Tecrube seviyesi</label>
                        <select class="input" id="vacancySeniority" name="seniority_level">
                            @foreach ($levels as $value => $level)
                                <option value="{{ $value }}"
                                    {{ (string) old('seniority_level', $vacancy->seniority_level->value) === $value ? 'selected' : '' }}>
                                    {{ $level }}
                                </option>
                            @endforeach
                        </select>
                        @error('seniority_level')
                            <div class="mt-2 text-xs text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="label" for="vacancyStatusInput">Status</label>
                        <select class="input" id="vacancyStatusInput" name="status">
                            @foreach ($statuses as $value => $status)
                                <option value="{{ $value }}"
                                    {{ (string) old('status', $vacancy->status->value) === $value ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="mt-2 text-xs text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="label" for="vacancyLocation">Location</label>
                        <input class="input" id="vacancyLocation" name="location"
                            value="{{ old('location', $vacancy->location) }}">
                        @error('location')
                            <div class="mt-2 text-xs text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="label" for="vacancyCloseDate">Close date</label>
                        <div class="date-picker-shell" data-date-picker>
                            <input class="input pr-12" id="vacancyCloseDateDisplay" value="{{ $closedDateDisplay }}"
                                placeholder="dd-mm-yyyy" inputmode="numeric" autocomplete="off">
                            <button class="date-picker-toggle" type="button" data-date-picker-toggle
                                aria-label="Open calendar">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </button>
                            <div class="date-picker-panel hidden" data-date-picker-panel></div>
                        </div>
                        <input type="hidden" id="vacancyCloseDate" name="closed_at" value="{{ $closedDateValue }}">
                        @error('closed_at')
                            <div class="mt-2 text-xs text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="label" for="vacancyMinSalary">Min salary</label>
                        <input class="input" id="vacancyMinSalary" name="min_salary"
                            value="{{ old('min_salary', $vacancy->min_salary) }}">
                        @error('min_salary')
                            <div class="mt-2 text-xs text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="label" for="vacancyMaxSalary">Max salary</label>
                        <input class="input" id="vacancyMaxSalary" name="max_salary"
                            value="{{ old('max_salary', $vacancy->max_salary) }}">
                        @error('max_salary')
                            <div class="mt-2 text-xs text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="card">
                    <div class="vacancy-section-header">
                        <div>
                            <div class="text-base font-display font-700 text-white">Description</div>
                        </div>
                    </div>
                    <textarea class="input" id="vacancyDescription" name="description" rows="8">{{ old('description', $vacancy->description) }}</textarea>
                    @error('description')
                        <div class="mt-2 text-xs text-red-400">{{ $message }}</div>
                    @enderror
                </div>
                <div class="card">
                    <div class="vacancy-section-header">
                        <div>
                            <div class="text-base font-display font-700 text-white">Requirements notes</div>
                        </div>
                    </div>
                    <textarea class="input" id="vacancyRequirementsText" name="requirements_text" rows="8">{{ old('requirements_text', $vacancy->requirements_text) }}</textarea>
                    @error('requirements_text')
                        <div class="mt-2 text-xs text-red-400">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div class="card col-span-4">
                    <div class="vacancy-section-header">
                        <div>
                            <div class="text-base font-display font-700 text-white">Structured requirements</div>
                            <div class="text-xs text-slate-500">These are backend-ready requirement objects for
                                matching and filtering.</div>
                        </div>
                    </div>
                    <div class="tag-builder tag-builder-extended">
                        <input class="input" id="requirementInput" placeholder="Requirement name">
                        <input class="input" id="requirementValue" placeholder="Value, level or years">
                        <select class="input" id="requirementType">
                            @foreach ($requirement_types as $value => $requirement_type)
                                <option value="{{ $value }}">{{ $requirement_type }}</option>
                            @endforeach
                        </select>
                        <select class="input" id="requirementWeight">
                            <option value="">Prioritet</option>
                            <option value="5">Yuksek</option>
                            <option value="3" selected>Orta</option>
                            <option value="1">Asagi</option>
                        </select>
                        <label class="tag-toggle"><input type="checkbox" id="requirementRequired" checked>
                            Required</label>
                        <button class="icon-action-btn tooltip" type="button" id="addRequirementButton"
                            data-tip="Add" aria-label="Add requirement">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                    <div class="preset-row"></div>
                    <div class="requirement-list" id="requirementList">
                        @foreach ($oldRequirements as $index => $requirement)
                            @php
                                $requirementLabel = is_array($requirement)
                                    ? $requirement['label'] ?? ''
                                    : $requirement->requirement_name ?? '';
                                $requirementValue = is_array($requirement)
                                    ? $requirement['value'] ?? ''
                                    : $requirement->requirement_value ?? '';
                                $requirementType = is_array($requirement)
                                    ? $requirement['type'] ?? ''
                                    : $requirement->requirement_type?->value ?? ($requirement->requirement_type ?? '');
                                $requirementWeight = (is_array($requirement)
                                    ? $requirement['weight'] ?? 3
                                    : $requirement->weight ?? 3);
                                $requirementRequired = filter_var(
                                    is_array($requirement)
                                        ? $requirement['required'] ?? false
                                        : $requirement->is_required ?? false,
                                    FILTER_VALIDATE_BOOLEAN,
                                );
                            @endphp
                            @if ($requirementLabel !== '')
                                <div class="requirement-pill {{ $requirementRequired ? 'is-required' : 'is-optional' }}"
                                    data-requirement-item data-label="{{ $requirementLabel }}"
                                    data-value="{{ $requirementValue }}" data-type="{{ $requirementType }}"
                                    data-weight="{{ $requirementWeight }}"
                                    data-required="{{ $requirementRequired ? 'true' : 'false' }}">
                                    <div>
                                        <strong>{{ $requirementLabel }}{{ $requirementValue !== '' ? ': ' . $requirementValue : '' }}</strong>
                                        <span>{{ $requirementType }} /
                                            {{ $requirementWeight === '' || $requirementWeight === null ? 'secilmeyib' : ($requirementWeight == 5 ? 'yuksek' : ($requirementWeight == 1 ? 'asagi' : 'orta')) }} /
                                            {{ $requirementRequired ? 'required' : 'preferred' }}</span>
                                    </div>
                                    <div class="requirement-pill-actions">
                                        <input type="hidden" name="vacancy_requirements[{{ $index }}][label]"
                                            value="{{ $requirementLabel }}" data-requirement-field="label">
                                        <input type="hidden" name="vacancy_requirements[{{ $index }}][value]"
                                            value="{{ $requirementValue }}" data-requirement-field="value">
                                        <input type="hidden" name="vacancy_requirements[{{ $index }}][type]"
                                            value="{{ $requirementType }}" data-requirement-field="type">
                                        <input type="hidden" name="vacancy_requirements[{{ $index }}][weight]"
                                            value="{{ $requirementWeight }}" data-requirement-field="weight">
                                        <input type="hidden" name="vacancy_requirements[{{ $index }}][required]"
                                            value="{{ $requirementRequired ? 1 : 0 }}" data-requirement-field="required">
                                        <button class="icon-action-btn tooltip" type="button"
                                            data-edit-requirement data-tip="Edit" aria-label="Edit requirement">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2.5 2.5 0 113.536 3.536L12.536 14.5a4 4 0 01-1.414.94l-3.122 1.041 1.04-3.121A4 4 0 019 11z" />
                                            </svg>
                                        </button>
                                        <button class="icon-action-btn danger tooltip" type="button"
                                            data-remove-requirement data-tip="Remove" aria-label="Remove requirement">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 7h12m-9 0V5a1 1 0 011-1h4a1 1 0 011 1v2m-7 0l1 12h4l1-12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @error('vacancy_requirements')
                        <div class="mt-2 text-xs text-red-400">{{ $message }}</div>
                    @enderror
                    @error('vacancy_requirements.*.label')
                        <div class="mt-2 text-xs text-red-400">{{ $message }}</div>
                    @enderror
                    @error('vacancy_requirements.*.value')
                        <div class="mt-2 text-xs text-red-400">{{ $message }}</div>
                    @enderror
                    @error('vacancy_requirements.*.type')
                        <div class="mt-2 text-xs text-red-400">{{ $message }}</div>
                    @enderror
                    @error('vacancy_requirements.*.weight')
                        <div class="mt-2 text-xs text-red-400">{{ $message }}</div>
                    @enderror
                    @error('vacancy_requirements.*.required')
                        <div class="mt-2 text-xs text-red-400">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a class="btn-ghost" href="{{ route('vacancy.index') }}">Geri</a>
                <button class="btn-primary" type="submit">Yadda saxla</button>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/vacancy-form-page.js') }}"></script>
@endpush
