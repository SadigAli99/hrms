<?php

namespace App\Models;

use App\Enums\TalentPool\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TalentPool extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'note',
        'is_active',
        'added_by',
        'source_application_id',
        'source_vacancy_id',
        'candidate_id',
    ];

    protected $casts = [
        'category' => Category::class,
    ];

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(CandidateApplication::class, 'source_application_id');
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class, 'source_vacancy_id');
    }
}
