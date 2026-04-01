<?php

namespace App\Enums\Application;

enum DecisionReason : string {
    case SALARY_MISMATCH = 'salary_mismatch';
    case SKILL_MISMATCH = 'skill_mismatch';
    case EXPERIENCE_MISMATCH = 'experience_mismatch';
    case CULTURE_FIX_ISSUE = 'culture_fix_issue';
    case CANDIDATE_WITHDREW = 'candidate_withdrew';
    case POSITION_CLOSED = 'position_closed';
    case BETTER_CANDIDATE_SELECTED = 'better_candidate_selected';
    case PENDING = 'pending';
    case OTHER = 'other';
}