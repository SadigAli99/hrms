@extends('main.layouts.master')

@section('title', 'İcazələr')

@section('content')
    <div class="page-block space-y-6">
        @if (session('success_message'))
            <div class="card border border-green-500/20 bg-green-500/10">
                <div class="text-sm font-semibold text-green-300">{{ session('success_message') }}</div>
            </div>
        @endif

        <div class="card">
            <div class="vacancy-section-header">
                <div>
                </div>
                <a class="btn-primary btn-with-icon" href="{{ route('permission.create') }}">
                    <svg class="btn-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Əlavə et</span>
                </a>
            </div>
            <div class="vacancy-toolbar">
                <div class="vacancy-toolbar-group">
                    <input class="input" id="search" oninput="filter()" value="{{ request('search') }}" data-list-search
                        placeholder="Search...">
                </div>
                <div class="vacancy-toolbar-group vacancy-toolbar-slim">
                    <select class="input" id="status" onchange="filter()" data-list-filter="status">
                        <option value="">All statuses</option>
                        <option value="1" {{ (string)request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ (string)request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card" id="table-wrap">
            @include('main.pages.permissions.partials.list')
        </div>


    </div>

    @include('main.pages.permissions.partials.delete')
@endsection

@push('js')
    <script src="{{ asset('assets/js/crud-actions.js') }}"></script>
    <script>
        let filterTimer = null;

        function filter(page = 1) {
            if (typeof event !== 'undefined' && event) event.preventDefault();

            let tableWrap = document.getElementById('table-wrap');
            let searchEl = document.getElementById('search');
            let statusEl = document.getElementById('status');

            let search = (searchEl?.value ?? '').trim();
            let status = (statusEl?.value ?? '').trim();

            let fetchUrl =
                `{{ route('permission.filter') }}?search=${encodeURIComponent(search)}&status=${encodeURIComponent(status)}&page=${encodeURIComponent(page)}`;

            console.log(fetchUrl);

            let current = new URL(window.location.href);

            if (search) current.searchParams.set('search', search);
            else current.searchParams.delete('search');

            if (status) current.searchParams.set('status', status);
            else current.searchParams.delete('status');

            if (Number(page) > 1) current.searchParams.set('page', page);
            else current.searchParams.delete('page');

            history.pushState({}, '', current.toString());

            tableWrap.innerHTML = `
                <div style="padding:16px; text-align:center;">
                    <div style="margin-bottom:8px;">Yüklənir...</div>
                    <div style="opacity:.6; font-size:12px;">Zəhmət olmasa gözləyin</div>
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
                    <div style="padding:16px; text-align:center; color:#ef4444;">
                        Xəta baş verdi. Yenidən yoxlayın.
                    </div>
                `;
                });

        }
    </script>
@endpush
