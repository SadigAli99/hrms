<div class="modal-overlay" id="candidateRetryParseModal">
    <div class="modal modal-compact">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="font-display font-700 text-xl text-white">Retry parse</h2>
                <div class="text-xs text-slate-500 mt-1">The selected CV will be sent to AI analysis again.</div>
            </div>
            <button class="text-slate-500 hover:text-white transition-colors" type="button"
                data-action="close-retry-parse-modal">X</button>
        </div>

        <div class="card">
            <div class="text-sm text-slate-300">Selected file</div>
            <div class="mt-2 font-600 text-white" id="candidateRetryParseName">CV file</div>
        </div>

        <div class="flex justify-end gap-3 mt-4">
            <button class="btn-ghost" type="button" data-action="close-retry-parse-modal">Cancel</button>
            <button class="btn-primary" type="button" id="candidateRetryParseConfirm">Retry parse</button>
        </div>
    </div>
</div>
