<?php

namespace App\Models;

use App\Enums\Vacancy\EmploymentType;
use App\Enums\Vacancy\Level;
use App\Enums\Vacancy\Status;
use App\Enums\Vacancy\WorkMode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vacancy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'employment_type',
        'work_mode',
        'seniority_level',
        'min_salary',
        'max_salary',
        'currency',
        'location',
        'description',
        'requirements_text',
        'status',
        'closed_at',
        'created_by',
        'department_id',
    ];

    protected $casts = [
        'employment_type' => EmploymentType::class,
        'work_mode' => WorkMode::class,
        'seniority_level' => Level::class,
        'status' => Status::class,
        'min_salary' => 'float',
        'max_salary' => 'float',
        'closed_at' => 'datetime',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(VacancyRequirement::class, 'vacancy_id');
    }
}
