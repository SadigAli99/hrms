<?php

namespace App\Models;

use App\Enums\Candidate\ProfileStatus;
use App\Enums\Candidate\SourceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'location',
        'linkedin_url',
        'current_title',
        'total_experience_years',
        'highest_education',
        'source_type',
        'profile_status',
        'last_cv_file_id',
    ];

    protected $casts = [
        'total_experience_years' => 'float',
        'source_type' => SourceType::class,
        'profile_status' => ProfileStatus::class,
    ];

    public function cv_files(): HasMany
    {
        return $this->hasMany(CandidateCvFile::class);
    }

    public function latest_cv()
    {
        return $this->cv_files()->orderBy('id', 'desc')->first();
    }

    public function last_cv_file(): BelongsTo
    {
        return $this->belongsTo(CandidateCvFile::class, 'last_cv_file_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(CandidateApplication::class, 'candidate_id');
    }

    public function profiles(): HasMany
    {
        return $this->hasMany(CandidateProfile::class);
    }
}
