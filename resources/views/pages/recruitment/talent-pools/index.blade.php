@extends('layouts.master')

@section('title', 'Talent pool')

@section('content')
    @php
        $talentPoolCount = method_exists($talent_pools, 'total') ? $talent_pools->total() : $talent_pools->count();
        $vacancyOptions = $vacancies ?? collect();
        $categoryOptions = \App\Enums\TalentPool\Category::cases();
    @endphp

    <div class="page-block space-y-6">
        <div id="talentPoolNotice" class="empty-state" style="display:none;"></div>

        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="font-display font-700 text-2xl text-white">Talent Pool</h1>
                <div class="text-sm text-slate-500 mt-1">
                    <span class="text-brand-400 font-600">{{ $talentPoolCount }}</span> namizəd saxlanılıb
                </div>
            </div>
        </div>

        <div class="card">
            <form method="GET" action="{{ route('talent-pool.index') }}" class="grid grid-cols-4 gap-4">
                <div>
                    <label class="label" for="search">Axtarış</label>
                    <input class="input" id="search" name="search" type="text" value="{{ request('search') }}"
                        placeholder="Ad, rol və ya bacarıq axtar" />
                </div>

                <div>
                    <label class="label" for="vacancy">Vakansiya</label>
                    <select class="input" id="vacancy" name="vacancy_id">
                        <option value="">Bütün vakansiyalar</option>
                        @foreach ($vacancyOptions as $vacancy)
                            <option value="{{ $vacancy->id }}" @selected((string) request('vacancy_id') === (string) $vacancy->id)>
                                {{ $vacancy->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="label" for="category">Kateqoriya</label>
                    <select class="input" id="category" name="category">
                        <option value="">Bütün kateqoriyalar</option>
                        @foreach ($categoryOptions as $category)
                            <option value="{{ $category->value }}" @selected(request('category') === $category->value)>
                                {{ $category->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-3">
                    <button class="btn-primary px-3 flex items-center justify-center gap-2 tooltip" type="submit"
                        data-tip="Filter" onclick="filter()">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6L14 13.5V19a1 1 0 01-1.447.894l-2-1A1 1 0 0110 18v-4.5L3.2 4.6A1 1 0 013 4z" />
                        </svg>
                    </button>
                    <a class="btn-ghost px-3 flex items-center justify-center tooltip"
                        href="{{ route('talent-pool.index') }}" data-tip="Sıfırla" aria-label="Sıfırla">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9M4.582 9H9m11 11v-5h-.581m0 0A8.003 8.003 0 015.03 15m14.389 0H15" />
                        </svg>
                    </a>
                </div>
            </form>
        </div>

        <div id="talentPoolResults">
            <div class="grid grid-cols-3 gap-4" id="table-wrap">
                @include('pages.recruitment.talent-pools.partials.list')
            </div>

            @if ($talent_pools instanceof \Illuminate\Contracts\Pagination\Paginator && $talent_pools->hasPages())
                <div id="talentPoolPagination" class="mt-4">
                    {{ $talent_pools->appends(request()->query())->links('vendor.pagination.hrms') }}
                </div>
            @endif
        </div>
    </div>

    @include('pages.recruitment.talent-pools.partials.talent-reuse')
@endsection

@push('js')
    <script src="{{ asset('assets/js/talent-page.js') }}"></script>
    <script>
        let filterTimer = null;

        function filter(page = 1) {
            if (typeof event !== 'undefined' && event) event.preventDefault();

            let tableWrap = document.getElementById('table-wrap');
            let paginationWrap = document.getElementById('talentPoolPagination');
            let searchEl = document.getElementById('search');
            let vacancyEl = document.getElementById('vacancy');
            let categoryEl = document.getElementById('category');

            let search = (searchEl?.value ?? '').trim();
            let vacancy_id = (vacancyEl?.value ?? '').trim();
            let category = (categoryEl?.value ?? '').trim();

            let fetchUrl =
                `{{ route('talent-pool.filter') }}?search=${encodeURIComponent(search)}&vacancy_id=${encodeURIComponent(vacancy_id)}&category=${encodeURIComponent(category)}&page=${encodeURIComponent(page)}`;

            let current = new URL(window.location.href);

            if (search) current.searchParams.set('search', search);
            else current.searchParams.delete('search');

            if (category) current.searchParams.set('category', category);
            else current.searchParams.delete('category');

            if (vacancy_id) current.searchParams.set('vacancy_id', vacancy_id);
            else current.searchParams.delete('vacancy_id');

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
                        if (paginationWrap && typeof data.pagination !== 'undefined') {
                            paginationWrap.innerHTML = data.pagination || '';
                        }
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

        document.addEventListener('click', function(e) {
            const paginationLink = e.target.closest('#talentPoolPagination a');
            if (!paginationLink) return;

            e.preventDefault();

            try {
                const url = new URL(paginationLink.href);
                filter(url.searchParams.get('page') || 1);
            } catch (_) {}
        });
    </script>
@endpush
