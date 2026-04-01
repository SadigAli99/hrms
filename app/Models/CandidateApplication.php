<?php

namespace App\Models;

use App\Enums\Application\DecisionReason;
use App\Enums\Application\Source;
use App\Enums\Application\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CandidateApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_source',
        'status',
        'ai_score',
        'applied_at',
        'shortlisted_at',
        'decision_reason',
        'notes',
        'vacancy_id',
        'candidate_id',
        'owner_user_id',
    ];

    protected $casts = [
        'application_source' => Source::class,
        'status' => Status::class,
        'ai_score' => 'float',
        'applied_at' => 'datetime',
        'shortlisted_at' => 'datetime',
        'decision_reason' => DecisionReason::class,
    ];

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function analyses(): HasMany
    {
        return $this->hasMany(AiAnalysis::class, 'application_id');
    }
}
