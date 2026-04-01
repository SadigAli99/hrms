<?php

namespace App\Repositories\Implementations;

use App\Models\CandidateProfile;
use App\Repositories\Interfaces\CandidateProfileInterface;

class CandidateProfileRepository extends GenericRepository implements CandidateProfileInterface
{
    public function __construct()
    {
        $this->model = CandidateProfile::class;
    }

    public function getByCandidateId(int $candidateId)
    {
        return $this->model::query()
            ->where('candidate_id', $candidateId)
            ->first();
    }
}
