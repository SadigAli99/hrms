<?php

namespace App\Repositories\Interfaces;

interface PermissionInterface extends GenericInterface
{
    public function getByGroup() : array;

    public function filter(array $data);

    public function export(array $data);
}
