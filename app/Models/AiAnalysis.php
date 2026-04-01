<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'overall_score',
        'skills_score',
        'experience_score',
        'education_score',
        'languages_score',
        'pros_text',
        'cons_text',
        'matched_skills_json',
        'missing_skills_json',
        'critical_missing_json',
        'risk_flags_json',
        'requirement_matches_json',
        'seniority_fit',
        'salary_fit',
        'notes_json',
        'analysis_type',
        'analysis_version',
        'analyzed_at',
        'application_id',
    ];

    protected $casts = [
        'overall_score' => 'float',
        'skills_score' => 'float',
        'experience_score' => 'float',
        'education_score' => 'float',
        'languages_score' => 'float',
        'matched_skills_json' => 'array',
        'missing_skills_json' => 'array',
        'critical_missing_json' => 'array',
        'risk_flags_json' => 'array',
        'requirement_matches_json' => 'array',
        'notes_json' => 'array',
        'analyzed_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(CandidateApplication::class, 'application_id');
    }
}
