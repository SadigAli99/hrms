<?php

namespace App\Repositories\Interfaces;

interface DepartmentInterface extends GenericInterface
{
    public function filter(array $data);

    public function export(array $data);
}
