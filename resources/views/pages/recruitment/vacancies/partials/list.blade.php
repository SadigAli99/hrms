<div class="vacancy-board">
    @forelse ($vacancies as $vacancy)
        <article class="card vacancy-card" data-vacancy-card data-crud-item data-delete-entity="{{ $vacancy->title }}"
            data-delete-action="{{ route('vacancy.destroy', $vacancy->id) }}">
            <div class="vacancy-card-top">
                <div>
                    <div class="text-xs text-slate-500">{{ $vacancy->department->name }} /
                        {{ $vacancy->work_mode->label() }}
                        / {{ $vacancy->employment_type->label() }}</div>
                    <h3 class="vacancy-card-title">{{ $vacancy->title }}</h3>
                    <p class="text-sm text-slate-400">{{ $vacancy->location }} / closes
                        {{ $vacancy->closed_at->format('d M Y') }}</p>
                </div>
                <span class="badge {{ $vacancy->status->color() }}">{{ $vacancy->status->label() }}</span>
            </div>
            <div class="vacancy-card-metrics">
                <div><strong>47</strong><span>Applicants</span></div>
                <div><strong>3</strong><span>Interviews</span></div>
                <div><strong>Live</strong><span>Pipeline</span></div>
            </div>
            <div class="vacancy-card-tags">
                @foreach ($vacancy->requirements as $requirement)
                    <span
                        class="vacancy-chip {{ $requirement->is_required ? 'is-required' : '' }}">{{ $requirement->requirement_name }}</span>
                @endforeach
            </div>
            <p class="vacancy-card-copy">{{ $vacancy->description }}</p>
            <div class="vacancy-card-actions">
                <a class="btn-primary btn-with-icon" href="{{ route('vacancy.candidates', $vacancy->id) }}">
                    <svg class="btn-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8h2a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2v-8a2 2 0 012-2h2m10 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0H7" />
                    </svg>
                    <span>Namizedler</span>
                </a>
                <a class="btn-ghost btn-with-icon" href="{{ route('vacancy.edit', $vacancy->id) }}">
                    <svg class="btn-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5h2m-1-1v2m-6 9l8-8 3 3-8 8H6v-3z" />
                    </svg>
                    <span>Duzelt</span>
                </a>
                <button class="btn-danger btn-with-icon" type="button" data-crud-delete-trigger>
                    <svg class="btn-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 7h12m-9 0V5a1 1 0 011-1h4a1 1 0 011 1v2m-7 4v6m4-6v6m5-10l-1 12a2 2 0 01-2 2H9a2 2 0 01-2-2L6 7" />
                    </svg>
                    <span>Sil</span>
                </button>
            </div>
        </article>
    @empty
        <div class="card empty-state"
            style="grid-column: 1 / -1; min-height: 260px; display:flex; align-items:center; justify-content:center; flex-direction:column;">
            <div class="text-sm font-semibold text-white">Melumat tapilmadi</div>
            <div class="mt-2 text-xs text-slate-500">Filter ve ya axtaris neticesine uygun vakansiya yoxdur.</div>
        </div>
    @endforelse
</div>

@if ($vacancies instanceof \Illuminate\Contracts\Pagination\Paginator && $vacancies->hasPages())
    <div class="card">
        {{ $vacancies->links('vendor.pagination.hrms') }}
    </div>
@endif
