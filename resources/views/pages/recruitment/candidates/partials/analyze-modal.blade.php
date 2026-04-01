<div class="modal-overlay" id="candidateAnalyzeModal">
    <div class="modal">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="font-display font-700 text-xl text-white">AI Analysis</h2>
                <div class="text-xs text-slate-500 mt-1">Select which uploaded CV files should be analyzed for this
                    vacancy.</div>
            </div>
            <button class="text-slate-500 hover:text-white transition-colors" type="button"
                data-action="close-analyze-modal">X</button>
        </div>

        <div class="upload-list mb-4" id="candidateAnalyzeList">
            @forelse ($cv_files as $cvFile)
                <label class="analysis-option">
                    <input type="checkbox" data-analyze-file value="{{ $cvFile->id }}" checked>
                    <div>
                        <strong>{{ $cvFile->original_name }}</strong>
                        <span>{{ $cvFile->file_type ?? 'FILE' }} / {{ optional($cvFile->created_at)->format('d.m.Y H:i') }}</span>
                    </div>
                </label>
            @empty
                <div class="empty-state">No uploaded files available for analysis.</div>
            @endforelse
        </div>

        <div class="analysis-progress" id="candidateAnalyzeProgress" hidden>
            <div class="analysis-spinner"></div>
            <div>
                <div class="text-sm font-600 text-white">AI analysis in progress...</div>
                <div class="text-xs text-slate-500 mt-1">Selected CV files are being sent for analysis.</div>
            </div>
        </div>

        <div class="flex items-center justify-between gap-3 mt-4">
            <button class="btn-ghost" type="button" id="selectAllAnalyzeFiles">Select all</button>
            <div class="flex gap-3">
                <button class="btn-ghost" type="button" data-action="close-analyze-modal">Cancel</button>
                <button class="btn-primary" type="button" id="confirmCandidateAnalysis"
                    data-bulk-analyze-url="{{ \Illuminate\Support\Facades\Route::has('candidate.bulk-analyze') ? route('candidate.bulk-analyze') : '' }}">
                    Analyze selected
                </button>
            </div>
        </div>
    </div>
</div>
