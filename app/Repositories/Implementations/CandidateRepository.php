<?php

namespace App\Repositories\Implementations;

use App\Models\Candidate;
use App\Repositories\Interfaces\CandidateInterface;

class CandidateRepository extends GenericRepository implements CandidateInterface
{

    public function __construct()
    {
        $this->model = Candidate::class;
    }

    public function getByEmailOrPhone(array $data)
    {
        return $this->model::query()
            ->when($data['email'] ?? null, function ($query) use ($data) {
                $query->orWhere('email', $data['email']);
            })
            ->when($data['phone'] ?? null, function ($query) use ($data) {
                $query->orWhere('phone', $data['phone']);
            })
            ->first();
    }
}
