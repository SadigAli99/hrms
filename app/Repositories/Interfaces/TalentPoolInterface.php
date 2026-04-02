<?php

namespace App\Repositories\Interfaces;

use App\Models\TalentPool;

interface TalentPoolInterface extends GenericInterface
{
    public function getByCandidateAndApplication(int $candidateId, int $sourceApplicationId);

    public function filter(array $data = []);

    public function add_to_vacancy(TalentPool $talentPool, array $data);
}
