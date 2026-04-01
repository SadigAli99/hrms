<div class="modal-overlay" id="candidateDeleteCvModal">
    <div class="modal modal-compact">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="font-display font-700 text-xl text-white">Delete CV</h2>
                <div class="text-xs text-slate-500 mt-1">This action will remove the selected CV file.</div>
            </div>
            <button class="text-slate-500 hover:text-white transition-colors" type="button"
                data-action="close-delete-cv-modal">X</button>
        </div>

        <div class="card">
            <div class="text-sm text-slate-300">Selected file</div>
            <div class="mt-2 font-600 text-white" id="candidateDeleteCvName">CV file</div>
        </div>

        <form class="mt-4" id="candidateDeleteCvForm" method="post">
            @csrf
            @method('DELETE')
            <div class="flex justify-end gap-3">
                <button class="btn-ghost" type="button" data-action="close-delete-cv-modal">Cancel</button>
                <button class="btn-danger" type="submit" id="candidateDeleteCvConfirm">Delete CV</button>
            </div>
        </form>
    </div>
</div>
