<?php

namespace App\Repositories\Interfaces;

interface UserInterface extends GenericInterface
{

    public function filter(array $data);

    public function export(array $data);
}
