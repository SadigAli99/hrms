<?php

namespace App\Repositories\Implementations;

use App\Models\AiAnalysis;
use App\Repositories\Interfaces\AiAnalysisInterface;

class AiAnalysisRepository extends GenericRepository implements AiAnalysisInterface
{
    public function __construct()
    {
        $this->model = AiAnalysis::class;
    }

    public function getByApplicationId(int $applicationId)
    {
        return $this->model::query()
            ->where('application_id', $applicationId)
            ->orderBy('id', 'desc')
            ->first();
    }
}
