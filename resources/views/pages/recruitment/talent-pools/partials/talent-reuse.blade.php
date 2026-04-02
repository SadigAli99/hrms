<div class="modal-overlay" id="talentReuseModal">
    <div class="modal">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="font-display font-700 text-xl text-white" id="talentReuseTitle">Vakansiyaya əlavə et</h2>
                <div class="text-xs text-slate-500 mt-1">Talent pool-dakı namizədi seçilən vakansiyaya əlavə et.</div>
            </div>
            <button class="text-slate-500 hover:text-white transition-colors" type="button"
                data-action="close-talent-reuse-modal">X</button>
        </div>
        <div class="mb-4">
            <label class="label" for="talentReuseVacancy">Vakansiya</label>
            <select class="input" id="talentReuseVacancy">
                <option value="">Vakansiya seç</option>
                @foreach (($vacancies ?? collect()) as $vacancy)
                    <option value="{{ $vacancy->id }}">{{ $vacancy->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="label" for="talentReuseNote">Qeyd</label>
            <textarea class="input" id="talentReuseNote" rows="4"
                placeholder="Bu namizəd niyə bu vakansiyaya əlavə olunur?"></textarea>
        </div>
        <div class="flex justify-end gap-3">
            <button class="btn-ghost" type="button" data-action="close-talent-reuse-modal">Ləğv et</button>
            <button class="btn-primary" type="button" id="talentReuseSave">Vakansiyaya əlavə et</button>
        </div>
    </div>
</div>
