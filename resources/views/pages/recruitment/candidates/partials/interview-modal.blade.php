<div class="modal-overlay" id="candidateInterviewModal">
    <div class="modal">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="font-display font-700 text-xl text-white" id="candidateInterviewTitle">Interview workflow</h2>
                <div class="text-xs text-slate-500 mt-1" id="candidateInterviewSubtitle">Schedule or close interview
                    stage.</div>
            </div>
            <button class="text-slate-500 hover:text-white transition-colors" type="button"
                data-action="close-interview-modal">X</button>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="label" for="candidateInterviewType">Interview type</label>
                <select class="input" id="candidateInterviewType">
                    <option value="HR Interview">HR Interview</option>
                    <option value="Technical Interview">Technical Interview</option>
                    <option value="Hiring Manager Interview">Hiring Manager Interview</option>
                    <option value="Final Interview">Final Interview</option>
                </select>
            </div>
            <div>
                <label class="label" for="candidateInterviewDate">Date and time</label>
                <input class="input" type="datetime-local" id="candidateInterviewDate">
            </div>
            <div>
                <label class="label" for="candidateInterviewInterviewer">Interviewer</label>
                <input class="input" id="candidateInterviewInterviewer" placeholder="Nicat Ismayilov">
            </div>
            <div>
                <label class="label" for="candidateInterviewOutcome">Interview outcome</label>
                <select class="input" id="candidateInterviewOutcome">
                    <option value="scheduled">Only schedule interview</option>
                    <option value="interviewed">Interview completed successfully</option>
                    <option value="rejected">Interview failed / reject candidate</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="label" for="candidateInterviewNote">Notes</label>
            <textarea class="input" id="candidateInterviewNote" rows="4"
                placeholder="Write interview note, reason or final decision context"></textarea>
        </div>

        <div class="flex justify-end gap-3">
            <button class="btn-ghost" type="button" data-action="close-interview-modal">Cancel</button>
            <button class="btn-primary" type="button" id="candidateInterviewSave">Save interview step</button>
        </div>
    </div>
</div>
