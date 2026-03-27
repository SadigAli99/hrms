@extends('layouts.master')

@section('title', 'Vakansiyalar')

@section('content')
    <div class="page-block space-y-6">

        <div class="grid grid-cols-4 gap-4">
            <div class="stat-card">
                <div class="text-xs text-slate-500">Total vacancies</div>
                <div class="mt-2 text-2xl font-display font-700 text-white">{{ vacancy_status_count($vacancies) }}</div>
            </div>
            <div class="stat-card">
                <div class="text-xs text-slate-500">Published</div>
                <div class="mt-2 text-2xl font-display font-700 text-white">
                    {{ vacancy_status_count($vacancies, [\App\Enums\Vacancy\Status::PUBLISHED]) }}</div>
            </div>
            <div class="stat-card">
                <div class="text-xs text-slate-500">Draft / paused</div>
                <div class="mt-2 text-2xl font-display font-700 text-white">
                    {{ vacancy_status_count($vacancies, [\App\Enums\Vacancy\Status::DRAFT]) }}</div>
            </div>
            <div class="stat-card">
                <div class="text-xs text-slate-500">Interview steps</div>
                <div class="mt-2 text-2xl font-display font-700 text-white">6</div>
            </div>
        </div>

        <div class="card">
            <div class="vacancy-section-header">
                <div>
                </div>
                <a class="btn-primary btn-with-icon" href="{{ route('vacancy.create') }}">
                    <svg class="btn-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Create vacancy</span>
                </a>
            </div>
            <div class="vacancy-toolbar">
                <div class="vacancy-toolbar-group">
                    <input class="input" id="search" value="{{ request('search') }}"
                        placeholder="Search title, department, location" oninput="filter()">
                </div>
                <div class="vacancy-toolbar-group vacancy-toolbar-slim">
                    <select class="input" id="department" onchange="filter()">
                        <option value="">Butun departamentler</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="vacancy-toolbar-group vacancy-toolbar-slim">
                    <select class="input" id="status" onchange="filter()">
                        <option value="">All statuses</option>
                        @foreach ($statuses as $value => $status)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                {{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div id="table-wrap">
            @include('pages.recruitment.vacancies.partials.list')
        </div>
    </div>

    @include('pages.recruitment.vacancies.partials.delete')
@endsection

@push('js')
    <script src="{{ asset('assets/js/crud-actions.js') }}"></script>

    <script>
        let filterTimer = null;

        function filter(page = 1) {
            if (typeof event !== 'undefined' && event) event.preventDefault();

            let tableWrap = document.getElementById('table-wrap');
            let searchEl = document.getElementById('search');
            let departmentEl = document.getElementById('department');
            let statusEl = document.getElementById('status');

            let search = (searchEl?.value ?? '').trim();
            let status = (statusEl?.value ?? '').trim();
            let department = (departmentEl?.value ?? '').trim();

            let fetchUrl =
                `{{ route('vacancy.filter') }}?search=${encodeURIComponent(search)}&department_id=${encodeURIComponent(department)}&status=${encodeURIComponent(status)}&page=${encodeURIComponent(page)}`;

            let current = new URL(window.location.href);

            if (search) current.searchParams.set('search', search);
            else current.searchParams.delete('search');

            if (department) current.searchParams.set('department_id', department);
            else current.searchParams.delete('department_id');

            if (status) current.searchParams.set('status', status);
            else current.searchParams.delete('status');

            if (Number(page) > 1) current.searchParams.set('page', page);
            else current.searchParams.delete('page');

            history.pushState({}, '', current.toString());

            tableWrap.innerHTML = `
                <div class="card empty-state" style="grid-column: 1 / -1; min-height: 260px; display:flex; align-items:center; justify-content:center; flex-direction:column;">
                    <div class="text-sm font-semibold text-white">Yuklenir...</div>
                    <div class="mt-2 text-xs text-slate-500">Zehmet olmasa gozleyin</div>
                </div>
            `;

            fetch(fetchUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) return;

                    if (filterTimer) clearTimeout(filterTimer);

                    filterTimer = setTimeout(() => {
                        tableWrap.innerHTML = data.view;
                    }, 1000);
                })
                .catch(() => {
                    tableWrap.innerHTML = `
                        <div class="card empty-state" style="grid-column: 1 / -1; min-height: 260px; display:flex; align-items:center; justify-content:center; flex-direction:column; color:#ef4444;">
                            Xeta bas verdi. Yeniden yoxlayin.
                        </div>
                    `;
                });

        }
    </script>
@endpush
