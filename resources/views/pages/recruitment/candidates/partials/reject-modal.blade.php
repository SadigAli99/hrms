<div class="modal-overlay" id="candidateRejectModal">
    <div class="modal">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="font-display font-700 text-xl text-white" id="candidateRejectTitle">Reject Candidate</h2>
                <div class="text-xs text-slate-500 mt-1">Choose the rejection reason so the history stays useful later.
                </div>
            </div>
            <button class="text-slate-500 hover:text-white transition-colors" type="button"
                data-action="close-reject-modal">X</button>
        </div>

        <div class="mb-4">
            <label class="label" for="candidateRejectReason">Reason</label>
            <select class="input" id="candidateRejectReason">
                <option value="skill_mismatch">Skill mismatch</option>
                <option value="salary_mismatch">Salary mismatch</option>
                <option value="better_candidate_selected">Better candidate selected</option>
                <option value="experience_mismatch">Experience mismatch</option>
                <option value="candidate_withdrew">Candidate withdrew</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="label" for="candidateRejectNote">Reject note</label>
            <textarea class="input" id="candidateRejectNote" rows="4"
                placeholder="Write a short explanation for future reference"></textarea>
        </div>

        <div class="flex justify-end gap-3">
            <button class="btn-ghost" type="button" data-action="close-reject-modal">Cancel</button>
            <button class="btn-danger" type="button" id="candidateRejectSave">Reject candidate</button>
        </div>
    </div>
</div>
