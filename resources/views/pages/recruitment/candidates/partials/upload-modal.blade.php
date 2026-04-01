<div class="modal-overlay" id="candidateUploadModal">
    <div class="modal">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="font-display font-700 text-xl text-white">CV Upload</h2>
                <div class="text-xs text-slate-500 mt-1">Select multiple CV files or a zip package. Remove anything
                    before upload.</div>
            </div>
            <button class="text-slate-500 hover:text-white transition-colors" type="button"
                data-action="close-upload-modal">X</button>
        </div>

        <div class="dropzone mb-4" id="candidateDropzone">
            <input id="candidateFileInput" type="file" multiple
                accept=".pdf,.doc,.docx,.zip,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/zip"
                hidden>
            <div class="dropzone-icon">
                <svg class="w-6 h-6 text-brand-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
            </div>
            <h3 class="text-base font-display font-700 text-white">Choose files</h3>
            <p class="text-xs text-slate-500">PDF, DOC, DOCX and ZIP are accepted in this mock flow.</p>
        </div>

        <div class="upload-list mb-4" id="candidateUploadQueue">
            <div class="empty-state">No files selected yet.</div>
        </div>

        <div class="flex items-center justify-between gap-3">
            <button class="btn-ghost" type="button" id="clearCandidateQueue" disabled>Clear queue</button>
            <div class="flex gap-3">
                <button class="btn-ghost" type="button" data-action="close-upload-modal">Cancel</button>
                <button class="btn-primary" type="button" id="confirmCandidateUpload"
                    data-upload-url="{{ route('vacancy.upload-cv', $vacancy->id) }}" disabled>Upload selected</button>
            </div>
        </div>
    </div>
</div>
