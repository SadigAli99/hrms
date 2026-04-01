<div class="modal-overlay" id="candidateOfferModal">
    <div class="modal">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="font-display font-700 text-xl text-white" id="candidateOfferTitle">Offer Workflow</h2>
                <div class="text-xs text-slate-500 mt-1">Move the candidate to offer stage or close the hiring decision.
                </div>
            </div>
            <button class="text-slate-500 hover:text-white transition-colors" type="button"
                data-action="close-offer-modal">X</button>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="label" for="candidateOfferOutcome">Offer outcome</label>
                <select class="input" id="candidateOfferOutcome">
                    <option value="offer_pending">Move to offer pending</option>
                    <option value="hired">Candidate accepted and hired</option>
                    <option value="rejected">Offer failed / reject candidate</option>
                </select>
            </div>
            <div>
                <label class="label" for="candidateOfferComp">Compensation</label>
                <input class="input" id="candidateOfferComp" placeholder="e.g. 3200 AZN gross">
            </div>
        </div>

        <div class="mb-4">
            <label class="label" for="candidateOfferNote">Offer note</label>
            <textarea class="input" id="candidateOfferNote" rows="4"
                placeholder="Write offer context, package note or final hiring decision"></textarea>
        </div>

        <div class="flex justify-end gap-3">
            <button class="btn-ghost" type="button" data-action="close-offer-modal">Cancel</button>
            <button class="btn-primary" type="button" id="candidateOfferSave">Save offer step</button>
        </div>
    </div>
</div>
