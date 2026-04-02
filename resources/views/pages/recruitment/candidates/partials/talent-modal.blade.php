<div class="modal-overlay" id="candidateTalentModal">
    <div class="modal">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="font-display font-700 text-xl text-white" id="candidateTalentTitle">Talent Pool</h2>
                <div class="text-xs text-slate-500 mt-1">Namizədi gələcək vakansiyalar üçün necə saxlamaq istədiyini seç.
                </div>
            </div>
            <button class="text-slate-500 hover:text-white transition-colors" type="button"
                data-action="close-talent-modal">X</button>
        </div>

        <div class="space-y-3 mb-4" id="candidateTalentOptions">
            @foreach ($categories as $value => $category)
                <label class="analysis-option">
                    <input type="radio" name="candidateTalentCategory" value="{{ $value }}">
                    <div>
                        <strong>{{ $category['label'] }}</strong>
                        <span>{{ $category['description'] }}</span>
                    </div>
                </label>
            @endforeach
        </div>

        <div class="mb-4">
            <label class="label" for="candidateTalentNote">Qeyd</label>
            <textarea class="input" id="candidateTalentNote" rows="4"
                placeholder="Bu namizəd niyə talent pool-da saxlanılmalıdır?"></textarea>
        </div>

        <div class="flex justify-end gap-3">
            <button class="btn-ghost" type="button" data-action="close-talent-modal">Ləğv et</button>
            <button class="btn-primary" type="button" id="candidateTalentSave">Talent Pool-a əlavə et</button>
        </div>
    </div>
</div>
