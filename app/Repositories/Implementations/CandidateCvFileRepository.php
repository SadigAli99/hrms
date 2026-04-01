<?php

namespace App\Repositories\Implementations;

use App\Http\Traits\FileUploadTrait;
use App\Models\CandidateCvFile;
use App\Repositories\Interfaces\CandidateCvFileInterface;

class CandidateCvFileRepository extends GenericRepository implements CandidateCvFileInterface
{
    use FileUploadTrait;

    public function __construct()
    {
        $this->model = CandidateCvFile::class;
    }

    public function getPendingCvFiles(int $vacancyId)
    {
        return $this->model::query()
            ->where('vacancy_id', $vacancyId)
            ->orderBy('id', 'desc')
            ->paginate(10);
    }

    public function softDelete($model)
    {
        $this->fileDelete($model->file_path);
        $response = parent::softDelete($model);
        return $response;
    }
}
