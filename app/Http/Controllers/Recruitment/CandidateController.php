<?php

namespace App\Http\Controllers\Recruitment;

use App\Enums\Candidate\ParseStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Recruitment\Candidate\AnalyzeCvRequest;
use App\Http\Requests\Recruitment\Candidate\BulkAnalyzeCvRequest;
use App\Http\Requests\Recruitment\Candidate\CvFileRequest;
use App\Jobs\AnalyzeCandidateCvJob;
use App\Repositories\Interfaces\ApplicationInterface;
use App\Repositories\Interfaces\CandidateCvFileInterface;
use App\Repositories\Interfaces\VacancyInterface;
use App\Services\AnalyzeCandidateService;
use App\Services\UploadCandidateService;

class CandidateController extends Controller
{
    protected $applicationRepo;
    protected $vacancyRepo;
    protected $candidateCvFileRepo;
    protected $uploadCandidateService;
    protected $analyzeCandidateService;
    public function __construct(
        ApplicationInterface $applicationRepo,
        VacancyInterface $vacancyRepo,
        CandidateCvFileInterface $candidateCvFileRepo,
        UploadCandidateService $uploadCandidateService,
        AnalyzeCandidateService $analyzeCandidateService,
    ) {
        $this->applicationRepo = $applicationRepo;
        $this->vacancyRepo = $vacancyRepo;
        $this->candidateCvFileRepo = $candidateCvFileRepo;
        $this->uploadCandidateService = $uploadCandidateService;
        $this->analyzeCandidateService = $analyzeCandidateService;
    }

    public function index(string $id)
    {
        $vacancy = $this->vacancyRepo->getById($id);
        $applications = $this->applicationRepo->getApplications($id);
        $cv_files = $this->candidateCvFileRepo->getPendingCvFiles($id);
        return view('pages.recruitment.candidates.index', compact([
            'vacancy',
            'cv_files',
            'applications',
        ]));
    }

    public function upload_cv(string $id, CvFileRequest $request)
    {
        foreach ($request->file('files', []) as $file) {
            $this->uploadCandidateService->uploadFile($id, $file);
        }

        return response()->json([
            'success' => true,
            'message' => 'Fayllar uğurla yükləndi',
        ]);
    }

    public function delete_cv(string $id)
    {
        $cvFile = $this->candidateCvFileRepo->getById($id);
        $response = $this->candidateCvFileRepo->softDelete($cvFile);
        if (!$response['success']) return redirect()->back()->with('error_message', $response['message']);
        return redirect()->back()->with('success_message', $response['message']);
    }


    public function analyze_cv(AnalyzeCvRequest $request)
    {
        $cvFile = $this->candidateCvFileRepo->getById($request->cv_file_id);

        if (!$cvFile) {
            return response()->json([
                'success' => false,
                'message' => 'CV file tapilmadi',
            ], 404);
        }

        $this->candidateCvFileRepo->update($cvFile, [
            'parse_status' => ParseStatus::PROCESSING,
            'rejection_reason' => null,
        ]);

        AnalyzeCandidateCvJob::dispatch($cvFile->id, auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'CV analysis queued',
            'cv_file_id' => $cvFile->id,
        ]);
    }

    public function retry_parse(AnalyzeCvRequest $request)
    {
        $cvFile = $this->candidateCvFileRepo->getById($request->cv_file_id);

        if (!$cvFile) {
            return response()->json([
                'success' => false,
                'message' => 'CV file tapilmadi',
            ], 404);
        }

        $this->candidateCvFileRepo->update($cvFile, [
            'parse_status' => ParseStatus::PROCESSING,
            'rejection_reason' => null,
        ]);

        AnalyzeCandidateCvJob::dispatch($cvFile->id, auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'Retry parse queued',
            'cv_file_id' => $cvFile->id,

        ]);
    }

    public function bulk_analyze(BulkAnalyzeCvRequest $request)
    {
        foreach ($request->get('cv_file_ids', []) as $cv_file_id) {
            $cvFile = $this->candidateCvFileRepo->getById($cv_file_id);
            if (!$cvFile) {
                continue;
            }

            $this->candidateCvFileRepo->update($cvFile, [
                'parse_status' => ParseStatus::PROCESSING,
                'rejection_reason' => null,
            ]);

            AnalyzeCandidateCvJob::dispatch($cv_file_id, auth()->id());
        }

        return response()->json([
            'success' => true,
            'message' => 'Bulk analyze queued',
        ]);
    }
}
