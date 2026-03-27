<?php

namespace App\Models;

use App\Enums\Vacancy\RequirementType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VacancyRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'requirement_type',
        'requirement_name',
        'requirement_value',
        'weight',
        'is_required',
        'vacancy_id',
    ];

    protected $casts = [
        'requirement_type' => RequirementType::class,
        'weight' => 'float',
    ];

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(Vacancy::class, 'vacancy_id');
    }
}
