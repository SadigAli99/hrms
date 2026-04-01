<?php

namespace App\Repositories\Interfaces;

interface CandidateProfileInterface extends GenericInterface
{
    public function getByCandidateId(int $candidateId);
}
