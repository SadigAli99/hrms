<?php

namespace App\Repositories\Implementations;

use App\Models\CandidateApplication;
use App\Repositories\Interfaces\ApplicationInterface;

class ApplicationRepository extends GenericRepository implements ApplicationInterface
{
    public function __construct()
    {
        $this->model = CandidateApplication::class;
    }

    public function getApplications(int $vacancyId)
    {
        return $this->model::query()
            ->with('vacancy', 'candidate', 'candidate.last_cv_file', 'analyses')
            ->where('vacancy_id', $vacancyId)
            ->orderBy('id', 'desc')
            ->paginate(10);
    }

    public function getByCandidate(int $vacancyId, int $candidateId)
    {
        return $this->model::query()
            ->where('vacancy_id', $vacancyId)
            ->where('candidate_id', $candidateId)
            ->first();
    }
}
