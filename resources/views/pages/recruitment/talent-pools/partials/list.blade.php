@forelse ($talent_pools as $talentPool)
    @php
        $candidate = $talentPool->candidate;
        $sourceVacancy = $talentPool->vacancy;
        $categoryKey = $talentPool->category?->value ?? (string) $talentPool->category;
        $categoryLabel = $talentPool->category?->label() ?? ucfirst($categoryKey);
        $categoryClass = $talentPool->category?->color() ?? 'badge-blue';
        $skills = collect(optional($candidate?->profiles?->sortByDesc('id')->first())->skills_json ?? [])->filter()->take(4)->values();
        $experienceLabel = $candidate?->total_experience_years
            ? rtrim(rtrim((string) $candidate->total_experience_years, '0'), '.') . ' il'
            : 'Təcrübə yoxdur';
        $initials = collect(explode(' ', trim((string) ($candidate?->full_name ?? 'NA'))))
            ->filter()
            ->take(2)
            ->map(fn($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
        $sourceLabel = $sourceVacancy?->title ? 'Mənbə vakansiya: ' . $sourceVacancy->title : 'Mənbə vakansiya yoxdur';
        $profileLink = $sourceVacancy ? route('vacancy.candidates', $sourceVacancy->id) : '#';
    @endphp

    <article
        class="card card-hover"
        data-talent-card
        data-name="{{ $candidate?->full_name ?? 'Namizəd yoxdur' }}"
        data-role="{{ $candidate?->current_title ?? '' }}"
        data-skills="{{ $skills->implode(' ') }}"
        data-category="{{ $categoryKey }}"
        data-talent-pool-id="{{ $talentPool->id }}"
        data-candidate-id="{{ $candidate?->id }}"
        data-candidate-name="{{ $candidate?->full_name ?? 'Namizəd yoxdur' }}"
        data-source-vacancy-id="{{ $sourceVacancy?->id }}"
        data-add-url="{{ \Illuminate\Support\Facades\Route::has('talent-pool.add-to-vacancy') ? route('talent-pool.add-to-vacancy', $talentPool->id) : '' }}">
        <div class="flex items-start gap-3 mb-4">
            <div class="avatar bg-gradient-to-br from-brand-600 to-accent text-white w-12 h-12 text-sm flex-shrink-0">
                {{ $initials ?: 'NA' }}
            </div>
            <div class="flex-1">
                <div class="font-display font-700 text-white">{{ $candidate?->full_name ?? 'Namizəd yoxdur' }}</div>
                <div class="text-xs text-slate-500">
                    {{ $candidate?->current_title ?: 'Title yoxdur' }} / {{ $experienceLabel }}
                </div>
                <span class="badge {{ $categoryClass }} mt-1">{{ $categoryLabel }}</span>
            </div>
        </div>

        <div class="flex flex-wrap gap-1.5 mb-3">
            @forelse ($skills as $skill)
                <span class="badge badge-blue">{{ $skill }}</span>
            @empty
                <span class="badge badge-blue">Bacarıq qeyd olunmayıb</span>
            @endforelse
        </div>

        <div class="text-xs text-slate-500 mb-1">{{ $talentPool->note ?: 'Qeyd yoxdur.' }}</div>
        <div class="text-xs text-slate-500 mb-3">{{ $sourceLabel }}</div>

        <div class="flex gap-2">
            <button class="btn-primary text-xs py-1.5 flex-1 text-center" type="button" data-action="open-reuse-modal">
                Vakansiyaya əlavə et
            </button>
            <a class="btn-ghost text-xs py-1.5 px-3" href="{{ $profileLink }}">Profil</a>
        </div>
    </article>
@empty
    <div class="col-span-3">
        <div class="empty-state">Hələ talent pool namizədi yoxdur.</div>
    </div>
@endforelse
