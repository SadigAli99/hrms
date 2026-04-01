<?php

namespace App\Models;

use App\Enums\Candidate\ParseStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateCvFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_name',
        'file_path',
        'file_type',
        'file_size_bytes',
        'parse_status',
        'parsed_at',
        'is_latest',
        'uploaded_by',
        'vacancy_id',
        'candidate_id',
    ];

    protected $casts = [
        'parse_status' => ParseStatus::class,
        'parsed_at' => 'datetime',
    ];

    public function uploaded_by() : BelongsTo {
        return $this->belongsTo(User::class,'uploaded_by');
    }

    public function candidate() : BelongsTo{
        return $this->belongsTo(Candidate::class,'candidate_id');
    }

    public function vacancy() : BelongsTo {
        return $this->belongsTo(Vacancy::class,'vacancy_id');
    }
}
