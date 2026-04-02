<div class="modal-overlay" id="candidateProfileModal">
    <div class="modal modal-wide">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="font-display font-700 text-xl text-white" id="candidateProfileName">Namizəd profili</h2>
                <div class="text-xs text-slate-500 mt-1" id="candidateProfileMeta">AI analiz nəticələri</div>
            </div>
            <button class="text-slate-500 hover:text-white transition-colors" type="button"
                data-action="close-profile-modal">X</button>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="card">
                <div class="label">AI ortalaması</div>
                <div class="flex items-center gap-3">
                    <div class="score-ring score-ring-lg" id="candidateProfileScoreRing">
                        <svg width="72" height="72" viewBox="0 0 72 72">
                            <circle cx="36" cy="36" r="30" fill="none" stroke="#1c2640"
                                stroke-width="6" />
                            <circle id="candidateProfileScoreStroke" cx="36" cy="36" r="30" fill="none"
                                stroke="#3178f6" stroke-width="6" stroke-dasharray="188.5" stroke-dashoffset="188.5"
                                stroke-linecap="round" />
                        </svg>
                        <div class="score-text"><span class="text-sm font-800 text-white"
                                id="candidateProfileScore">0%</span></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs text-slate-500">Ümumi AI qiymətləndirməsi</div>
                        <div class="text-xs text-slate-500 mt-2" id="candidateProfileSummary">Xülasə yoxdur</div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="label">Mənbə</div>
                <div id="candidateProfileStatus"></div>
                <div class="text-xs text-slate-500 mt-2" id="candidateProfileExperience">Təcrübə</div>
                <div class="text-xs text-slate-500 mt-2" id="candidateProfileFitMeta">Fit details</div>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-4 mt-4">
            <div class="card profile-score-card">
                <div class="label">Bacarıq</div>
                <div class="profile-score-value is-blue" id="candidateProfileSkillsScore">0%</div>
            </div>
            <div class="card profile-score-card">
                <div class="label">Təcrübə</div>
                <div class="profile-score-value is-green" id="candidateProfileExperienceScore">0%</div>
            </div>
            <div class="card profile-score-card">
                <div class="label">Təhsil</div>
                <div class="profile-score-value is-purple" id="candidateProfileEducationScore">0%</div>
            </div>
            <div class="card profile-score-card">
                <div class="label">Dil</div>
                <div class="profile-score-value is-cyan" id="candidateProfileLanguageScore">0%</div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="vacancy-section-header">
                <div>
                    <div class="text-base font-display font-700 text-white">Uyğun bacarıqlar</div>
                    <div class="text-xs text-slate-500"></div>
                </div>
            </div>
            <div class="vacancy-card-tags" id="candidateProfileSkills"></div>
        </div>

        <div class="card mt-4">
            <div class="vacancy-section-header">
                <div>
                    <div class="text-base font-display font-700 text-white">Çatışmayan özəlliklər</div>
                    <div class="text-xs text-slate-500"></div>
                </div>
            </div>
            <div class="vacancy-card-tags" id="candidateProfileGaps"></div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-4">
            <div class="card">
                <div class="vacancy-section-header">
                    <div>
                        <div class="text-base font-display font-700 text-white">Kritik çatışmazlıqlar</div>
                        <div class="text-xs text-slate-500"></div>
                    </div>
                </div>
                <div class="profile-signal-list" id="candidateProfileCriticalMissing"></div>
            </div>
            <div class="card">
                <div class="vacancy-section-header">
                    <div>
                        <div class="text-base font-display font-700 text-white">Riskli məqamlar</div>
                        <div class="text-xs text-slate-500"></div>
                    </div>
                </div>
                <div class="profile-signal-list" id="candidateProfileRiskFlags"></div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="vacancy-section-header">
                <div>
                    <div class="text-base font-display font-700 text-white">Vakansiya tələbləri ilə müqayisə</div>
                    <div class="text-xs text-slate-500">AI nəticəsi ilə vakansiya tələblərinin qarşılaşdırılması.</div>
                </div>
            </div>
            <div class="profile-requirement-grid" id="candidateProfileRequirements"></div>
        </div>

        <div class="card mt-4">
            <div class="vacancy-section-header">
                <div>
                    <div class="text-base font-display font-700 text-white">Tarixçə və qeydlər</div>
                    <div class="text-xs text-slate-500">Bu namizəd üzrə əvvəlki proses qeydləri və yoxlanılmalı məqamlar.</div>
                </div>
            </div>
            <div class="upload-list" id="candidateProfileHistory"></div>
        </div>

        <div class="flex justify-end gap-3 mt-4">
            <button class="btn-ghost" type="button" data-action="close-profile-modal">Bağla</button>
            <button class="btn-ghost" type="button" id="candidateTalentAction">Talent Pool-a əlavə et</button>
            <button class="btn-danger" type="button" id="candidateRejectAction">İmtina</button>
            <button class="btn-ghost" type="button" id="candidateOfferAction">Təklif </button>
            <button class="btn-primary" type="button" id="candidateInterviewAction">Müsahibə</button>
        </div>
    </div>
</div>
