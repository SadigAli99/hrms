<?php

namespace App\Repositories\Interfaces;

interface ApplicationInterface extends GenericInterface
{
    public function getApplications(int $vacancyId);
    public function getByCandidate(int $vacancyId, int $candidateId);
}
