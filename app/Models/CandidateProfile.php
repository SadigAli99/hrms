<?php

namespace App\Models;

use App\Enums\Candidate\ParsedSource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'professional_summary',
        'skills_json',
        'experience_json',
        'education_json',
        'languages_json',
        'certifications_json',
        'parsed_source',
        'parser_version',
        'last_parsed_at',
        'candidate_id',
        'cv_file_id',
    ];

    protected $casts = [
        'skills_json' => 'array',
        'experience_json' => 'array',
        'education_json' => 'array',
        'languages_json' => 'array',
        'certifications_json' => 'array',
        'parsed_source' => ParsedSource::class,
        'last_parsed_at' => 'datetime',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function cv_file(): BelongsTo
    {
        return $this->belongsTo(CandidateCvFile::class, 'cv_file_id');
    }
}
