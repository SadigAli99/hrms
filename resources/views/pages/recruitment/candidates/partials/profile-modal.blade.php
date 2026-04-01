<div class="modal-overlay" id="candidateProfileModal">
    <div class="modal modal-wide">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="font-display font-700 text-xl text-white" id="candidateProfileName">Candidate Profile</h2>
                <div class="text-xs text-slate-500 mt-1" id="candidateProfileMeta">AI summary and history</div>
            </div>
            <button class="text-slate-500 hover:text-white transition-colors" type="button"
                data-action="close-profile-modal">X</button>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="card">
                <div class="label">AI Match</div>
                <div class="flex items-center gap-3">
                    <div class="score-ring score-ring-lg" id="candidateProfileScoreRing">
                        <svg width="72" height="72" viewBox="0 0 72 72">
                            <circle cx="36" cy="36" r="30" fill="none" stroke="#1c2640" stroke-width="6"/>
                            <circle id="candidateProfileScoreStroke" cx="36" cy="36" r="30" fill="none" stroke="#3178f6" stroke-width="6" stroke-dasharray="188.5" stroke-dashoffset="188.5" stroke-linecap="round"/>
                        </svg>
                        <div class="score-text"><span class="text-sm font-800 text-white" id="candidateProfileScore">0%</span></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs text-slate-500">Overall AI evaluation</div>
                        <div class="text-xs text-slate-500 mt-2" id="candidateProfileSummary">No summary</div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="label">Current status</div>
                <div id="candidateProfileStatus"></div>
                <div class="text-xs text-slate-500 mt-2" id="candidateProfileExperience">Experience</div>
                <div class="text-xs text-slate-500 mt-2" id="candidateProfileFitMeta">Fit details</div>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-4 mt-4">
            <div class="card profile-score-card">
                <div class="label">Skills Score</div>
                <div class="profile-score-value is-blue" id="candidateProfileSkillsScore">0%</div>
            </div>
            <div class="card profile-score-card">
                <div class="label">Experience Score</div>
                <div class="profile-score-value is-green" id="candidateProfileExperienceScore">0%</div>
            </div>
            <div class="card profile-score-card">
                <div class="label">Education Score</div>
                <div class="profile-score-value is-purple" id="candidateProfileEducationScore">0%</div>
            </div>
            <div class="card profile-score-card">
                <div class="label">Language Score</div>
                <div class="profile-score-value is-cyan" id="candidateProfileLanguageScore">0%</div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="vacancy-section-header">
                <div>
                    <div class="text-base font-display font-700 text-white">Matched skills</div>
                    <div class="text-xs text-slate-500">Skills that align with vacancy requirements.</div>
                </div>
            </div>
            <div class="vacancy-card-tags" id="candidateProfileSkills"></div>
        </div>

        <div class="card mt-4">
            <div class="vacancy-section-header">
                <div>
                    <div class="text-base font-display font-700 text-white">Missing or weak areas</div>
                    <div class="text-xs text-slate-500">Signals HR should verify during interview.</div>
                </div>
            </div>
            <div class="vacancy-card-tags" id="candidateProfileGaps"></div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-4">
            <div class="card">
                <div class="vacancy-section-header">
                    <div>
                        <div class="text-base font-display font-700 text-white">Critical missing</div>
                        <div class="text-xs text-slate-500">High-priority gaps that directly impact the score.</div>
                    </div>
                </div>
                <div class="profile-signal-list" id="candidateProfileCriticalMissing"></div>
            </div>
            <div class="card">
                <div class="vacancy-section-header">
                    <div>
                        <div class="text-base font-display font-700 text-white">Risk flags</div>
                        <div class="text-xs text-slate-500">AI-detected cautions HR should review.</div>
                    </div>
                </div>
                <div class="profile-signal-list" id="candidateProfileRiskFlags"></div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="vacancy-section-header">
                <div>
                    <div class="text-base font-display font-700 text-white">Vacancy requirements compare</div>
                    <div class="text-xs text-slate-500">AI nəticəsi ilə vakansiya tələblərinin qarşılaşdırılması.</div>
                </div>
            </div>
            <div class="profile-requirement-grid" id="candidateProfileRequirements"></div>
        </div>

        <div class="card mt-4">
            <div class="vacancy-section-header">
                <div>
                    <div class="text-base font-display font-700 text-white">Score reasoning</div>
                    <div class="text-xs text-slate-500">Why the final AI score moved up or down.</div>
                </div>
            </div>
            <div class="text-sm text-slate-300 leading-6" id="candidateProfileScoreReasoning">No score reasoning available.</div>
            <div class="profile-adjustment-list mt-4" id="candidateProfileScoreAdjustments"></div>
        </div>

        <div class="card mt-4">
            <div class="vacancy-section-header">
                <div>
                    <div class="text-base font-display font-700 text-white">History and notes</div>
                    <div class="text-xs text-slate-500">Previous process context for this candidate.</div>
                </div>
            </div>
            <div class="upload-list" id="candidateProfileHistory"></div>
        </div>

        <div class="flex justify-end gap-3 mt-4">
            <button class="btn-ghost" type="button" data-action="close-profile-modal">Close</button>
            <button class="btn-ghost" type="button" id="candidateTalentAction">Save to Talent Pool</button>
            <button class="btn-danger" type="button" id="candidateRejectAction">Reject</button>
            <button class="btn-ghost" type="button" id="candidateOfferAction">Offer action</button>
            <button class="btn-primary" type="button" id="candidateInterviewAction">Interview action</button>
        </div>
    </div>
</div>
