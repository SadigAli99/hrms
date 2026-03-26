<?php

namespace App\Repositories\Interfaces;

interface RoleInterface extends GenericInterface
{
    public function getPermissions(int $roleId);

    public function assignPermissions(int $roleId, array $data);

    public function filter(array $data = []);

    public function export(array $data);
}
