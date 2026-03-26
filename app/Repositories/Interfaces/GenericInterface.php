<?php

namespace App\Repositories\Interfaces;

interface GenericInterface
{
    function all();

    function getById(int $id);

    function create(array $data);

    function update($model, array $data);

    function softDelete($model);

    function hardDelete($model);
}
