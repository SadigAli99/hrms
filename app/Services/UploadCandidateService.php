<?php

namespace App\Services;

use App\Enums\Application\Source;
use App\Enums\Application\Status;
use App\Enums\Candidate\ParseStatus;
use App\Enums\Candidate\ProfileStatus;
use App\Enums\Candidate\SourceType;
use App\Http\Traits\FileUploadTrait;
use App\Repositories\Interfaces\ApplicationInterface;
use App\Repositories\Interfaces\CandidateCvFileInterface;
use App\Repositories\Interfaces\CandidateInterface;
use App\Repositories\Interfaces\VacancyInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UploadCandidateService
{
    use FileUploadTrait;

    protected $vacancyRepo;
    protected $candidateRepo;
    protected $applicationRepo;
    protected $candidateCvFileRepo;

    public function __construct(
        VacancyInterface $vacancyRepo,
        CandidateInterface $candidateRepo,
        ApplicationInterface $applicationRepo,
        CandidateCvFileInterface $candidateCvFileRepo
    ) {
        $this->vacancyRepo = $vacancyRepo;
        $this->candidateRepo = $candidateRepo;
        $this->applicationRepo = $applicationRepo;
        $this->candidateCvFileRepo = $candidateCvFileRepo;
    }

    public function findOrCreate(int $vacancyId, array $data)
    {
        DB::beginTransaction();

        try {
            $vacancy = $this->vacancyRepo->getById($vacancyId);

            $candidate = $this->candidateRepo->getByEmailOrPhone($data);

            if (!$candidate) {
                $candidateData = [
                    'full_name' => $data['full_name'],
                    'email' => $data['email'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'source_type' => SourceType::UPLOAD_CV,
                    'profile_status' => ProfileStatus::NEW,
                ];
                $candidate = $this->candidateRepo->create($candidateData);
            }

            $application = $this->applicationRepo->getByCandidate($vacancy->id, $candidate->id);
            if (!$application) {
                $applicationData = [
                    'vacancy_id' => $vacancy->id,
                    'candidate_id' => $candidate->id,
                    'owner_user_id' => auth()->id(),
                    'application_source' => Source::UPLOADED_CV,
                    'status' => Status::NEW,
                    'applied_at' => now(),
                ];
                $application = $this->applicationRepo->create($applicationData);
            }

            DB::commit();

            return [
                'vacancy' => $vacancy,
                'candidate' => $candidate,
                'application' => $application,
            ];
        } catch (\Throwable $ex) {
            DB::rollBack();
            Log::error('Upload candidate service failed', [
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
                'message' => $ex->getMessage(),
            ]);

            throw $ex;
        }
    }

    public function uploadFile(int $vacancyId, UploadedFile $file)
    {
        DB::beginTransaction();
        try {
            $originalName = $file->getClientOriginalName();
            $fileType = $file->getClientMimeType();
            $fileSize = $file->getSize();
            $image = $this->fileUpload($file, 'candidates');
            // $candidate = $this->candidateRepo->getById($candidateId);
            // foreach ($candidate->cv_files as $cv_file) {
            //     $this->candidateCvFileRepo->update($cv_file, ['is_latest' => 0]);
            // }
            $data = [
                'original_name' => $originalName,
                'file_path' => $image,
                'file_type' => $fileType,
                'file_size_bytes' => $fileSize,
                'parse_status' => ParseStatus::PENDING,
                'parsed_at' => now(),
                'is_latest' => 1,
                'uploaded_by' => auth()->id(),
                'vacancy_id' => $vacancyId,
                // 'candidate_id' => $candidateId,
            ];
            $candidateCvFile = $this->candidateCvFileRepo->create($data);
            // $this->candidateRepo->update($candidate, ['last_cv_file_id' => $candidateCvFile->id]);
            DB::commit();
            return [
                'cv_file' => $candidateCvFile,
            ];
        } catch (\Throwable $ex) {
            DB::rollBack();
            Log::error('CV File Upload service failed', [
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
                'message' => $ex->getMessage(),
            ]);
            throw $ex;
        }
    }
}
