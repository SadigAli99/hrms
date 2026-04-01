<?php

namespace App\Repositories\Interfaces;

interface CandidateInterface extends GenericInterface
{

    public function getByEmailOrPhone(array $data);
}
