<?php

namespace App\Repositories\Interfaces;

interface AiAnalysisInterface extends GenericInterface
{
    public function getByApplicationId(int $applicationId);
}
