<?php

namespace App\Repositories\Interfaces;

use App\Models\Vacancy;

interface VacancyInterface extends GenericInterface
{
    public function filter(array $data);

    public function export(array $data);

    public function add_requirements(Vacancy $vacancy, array $requirements);

    public function generate_requirement_text(array $requirements): string;
}
