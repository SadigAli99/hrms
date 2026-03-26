<?php

namespace App\Repositories\Implementations;

use App\Exceptions\CRUD\CreateException;
use App\Exceptions\CRUD\DeleteException;
use App\Exceptions\CRUD\EditException;
use App\Repositories\Interfaces\GenericInterface;
use Illuminate\Support\Facades\Log;

class GenericRepository implements GenericInterface
{
    protected $model;

    public function all()
    {
        return $this->model::query()->get();
    }

    public function getById(int $id)
    {
        return $this->model::findOrFail($id);
    }

    public function create(array $data)
    {
        try {
            $model = $this->model::create($data);
            return [
                'success' => true,
                'message' => 'Məlumat uğurla əlavə olundu',
                'item' => $model,
            ];
        } catch (CreateException $ex) {
            Log::error(json_encode([$ex->getMessage(), $ex->getLine(), $ex->getFile()]));
            return [
                'success' => false,
                'message' => $ex->getMessage(),
            ];
        }
    }

    public function update($model, array $data)
    {
        try {
            $model->update($data);
            return [
                'success' => true,
                'message' => 'Məlumat uğurla yeniləndi',
                'item' => $model,
            ];
        } catch (EditException $ex) {

            Log::error(json_encode([$ex->getMessage(), $ex->getLine(), $ex->getFile()]));

            return [
                'success' => false,
                'message' => $ex->getMessage(),
            ];
        }
    }

    public function softDelete($model)
    {
        try {
            $model->delete();
            return [
                'success' => true,
                'message' => 'Məlumat uğurla silindi',
            ];
        } catch (DeleteException $ex) {
            return [
                'success' => false,
                'message' => $ex->getMessage(),
            ];
        }
    }

    public function hardDelete($model)
    {
        try {
            $model->forceDelete();
            return [
                'success' => true,
                'message' => 'Məlumat uğurla silindi',
            ];
        } catch (DeleteException $ex) {
            return [
                'success' => false,
                'message' => $ex->getMessage(),
            ];
        }
    }
}
