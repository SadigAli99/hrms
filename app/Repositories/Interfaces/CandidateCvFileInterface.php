<?php

namespace App\Repositories\Interfaces;

interface CandidateCvFileInterface extends GenericInterface
{
    public function getPendingCvFiles(int $vacancyId);
}
