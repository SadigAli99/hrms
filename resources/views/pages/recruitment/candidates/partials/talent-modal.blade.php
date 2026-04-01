<div class="modal-overlay" id="candidateTalentModal">
    <div class="modal">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="font-display font-700 text-xl text-white" id="candidateTalentTitle">Talent Pool</h2>
                <div class="text-xs text-slate-500 mt-1">Choose how this candidate should be saved for future roles.
                </div>
            </div>
            <button class="text-slate-500 hover:text-white transition-colors" type="button"
                data-action="close-talent-modal">X</button>
        </div>

        <div class="space-y-3 mb-4" id="candidateTalentOptions">
            <label class="analysis-option">
                <input type="radio" name="candidateTalentCategory" value="recommended" checked>
                <div>
                    <strong>Recommended</strong>
                    <span>Strong profile to reuse soon for similar vacancies.</span>
                </div>
            </label>
            <label class="analysis-option">
                <input type="radio" name="candidateTalentCategory" value="watchlist">
                <div>
                    <strong>Watchlist</strong>
                    <span>Worth keeping visible, but not a near-term priority.</span>
                </div>
            </label>
            <label class="analysis-option">
                <input type="radio" name="candidateTalentCategory" value="future_fit">
                <div>
                    <strong>Future Fit</strong>
                    <span>Not right for this role, but useful for a different one later.</span>
                </div>
            </label>
        </div>

        <div class="mb-4">
            <label class="label" for="candidateTalentNote">Recruiter note</label>
            <textarea class="input" id="candidateTalentNote" rows="4"
                placeholder="Why should this candidate stay in the talent pool?"></textarea>
        </div>

        <div class="flex justify-end gap-3">
            <button class="btn-ghost" type="button" data-action="close-talent-modal">Cancel</button>
            <button class="btn-primary" type="button" id="candidateTalentSave">Save to Talent Pool</button>
        </div>
    </div>
</div>
