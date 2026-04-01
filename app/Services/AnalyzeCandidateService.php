<?php

namespace App\Services;

use App\Enums\Application\Source;
use App\Enums\Application\Status;
use App\Enums\Candidate\ParsedSource;
use App\Enums\Candidate\ParseStatus;
use App\Enums\Candidate\ProfileStatus;
use App\Enums\Candidate\SourceType;
use App\Repositories\Interfaces\ApplicationInterface;
use App\Repositories\Interfaces\AiAnalysisInterface;
use App\Repositories\Interfaces\CandidateCvFileInterface;
use App\Repositories\Interfaces\CandidateInterface;
use App\Repositories\Interfaces\CandidateProfileInterface;
use App\Repositories\Interfaces\VacancyInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class AnalyzeCandidateService
{
    protected $candidateRepo;
    protected $candidateCvFileRepo;
    protected $applicationRepo;
    protected $aiAnalysisRepo;
    protected $candidateProfileRepo;
    protected $extractCvTextService;
    protected $zipCvExtractorService;
    protected $openAiService;
    protected $vacancyRepo;

    public function __construct(
        CandidateInterface $candidateRepo,
        CandidateCvFileInterface $candidateCvFileRepo,
        ApplicationInterface $applicationRepo,
        AiAnalysisInterface $aiAnalysisRepo,
        CandidateProfileInterface $candidateProfileRepo,
        ExtractCVTextService $extractCvTextService,
        ZipCvExtractorService $zipCvExtractorService,
        OpenAIService $openAiService,
        VacancyInterface $vacancyRepo
    ) {
        $this->candidateRepo = $candidateRepo;
        $this->candidateCvFileRepo = $candidateCvFileRepo;
        $this->applicationRepo = $applicationRepo;
        $this->aiAnalysisRepo = $aiAnalysisRepo;
        $this->candidateProfileRepo = $candidateProfileRepo;
        $this->extractCvTextService = $extractCvTextService;
        $this->zipCvExtractorService = $zipCvExtractorService;
        $this->openAiService = $openAiService;
        $this->vacancyRepo = $vacancyRepo;
    }

    public function analyze(int $cvFileId, ?int $actorUserId = null): array
    {
        DB::beginTransaction();
        $tempPaths = [];
        $processedCount = 0;
        $candidate = null;
        $application = null;
        $profile = null;
        $analysis = null;


        try {
            $cvFile = $this->candidateCvFileRepo->getById($cvFileId);

            $documents = [];
            $skippedFiles = [];

            if (!$cvFile) {
                throw new \RuntimeException('CV file tapılmadı');
            }
            $vacancy = $this->vacancyRepo->getById($cvFile->vacancy_id);

            $filePath = public_path($cvFile->file_path);
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

            if ($extension === 'zip') {
                $zipResult = $this->zipCvExtractorService->extractCandidatesFromZip($filePath);

                if (!$zipResult['success']) {
                    throw new \RuntimeException($zipResult['error_message'] ?? 'Zip extraction failed');
                }

                foreach ($zipResult['processed_files'] as $file) {
                    $tempPaths[] = $file['path'];
                    $item = $this->extractCvTextService->extract($file['path'], $file['extension']);
                    if (!$item['success']) {
                        $skippedFiles[] = [
                            'file_name' => $file['file_name'],
                            'reason' => 'extract_failed',
                            'error_message' => $item['error_message'] ?? null,
                        ];
                        continue;
                    }

                    $storedPath = $this->storeExtractedZipDocument($file['path'], $file['extension']);
                    $documentCvFile = $this->candidateCvFileRepo->create([
                        'original_name' => $file['file_name'],
                        'file_path' => $storedPath,
                        'file_type' => $file['extension'],
                        'file_size_bytes' => $file['size'],
                        'parse_status' => ParseStatus::PENDING,
                        'parsed_at' => null,
                        'is_latest' => 1,
                        'uploaded_by' => $actorUserId,
                        'vacancy_id' => $cvFile->vacancy_id,
                        'candidate_id' => null,
                    ])['item'];

                    $documents[] = [
                        'file_name' => $file['file_name'],
                        'extension' => $file['extension'],
                        'text' => $item['text'],
                        'cv_file' => $documentCvFile,
                    ];
                }
            } else {
                $extracted = $this->extractCvTextService->extract($filePath, $extension);
                if (!$extracted['success']) {
                    throw new \RuntimeException($extracted['error_message'] ?? 'CV text extraction failed');
                }

                $documents[] = [
                    'file_name' => $cvFile->original_name,
                    'extension' => $extension,
                    'text' => $extracted['text'],
                    'cv_file' => $cvFile,
                ];
            }

            foreach ($documents as $document) {
                $documentCvFile = $document['cv_file'];

                $vacancyContext = [
                    'title' => $vacancy->title,
                    'seniority_level' => $vacancy->seniority_level?->value ?? null,
                    'min_salary' => $vacancy->min_salary,
                    'max_salary' => $vacancy->max_salary,
                    'currency' => $vacancy->currency ?? null,
                    'location' => $vacancy->location,
                    'description' => $vacancy->description,
                    'requirements_text' => $vacancy->requirements_text,
                ];

                $openAIResponse = $this->openAiService->analyze(
                    $document['text'],
                    $document['file_name'],
                    $vacancy->requirements->toArray(),
                    $vacancyContext
                );

                if (!$openAIResponse['success'] || !$openAIResponse['is_cv']) {
                    $this->candidateCvFileRepo->update($documentCvFile, [
                        'parse_status' => ParseStatus::FAILED,
                        'rejection_reason' => $openAIResponse['rejection_reason'] ?? null,
                    ]);
                    continue;
                }

                $parsedData = $openAIResponse['data'];

                $candidate = null;

                if ($documentCvFile->candidate_id) {
                    $candidate = $this->candidateRepo->getById($documentCvFile->candidate_id);
                } elseif (!empty($parsedData['email']) || !empty($parsedData['phone'])) {
                    $candidate = $this->candidateRepo->getByEmailOrPhone([
                        'email' => $parsedData['email'] ?? null,
                        'phone' => $parsedData['phone'] ?? null,
                    ]);
                }

                if (!$candidate) {

                    $candidateData = [
                        'full_name' => $parsedData['full_name'],
                        'email' => $parsedData['email'] ?? null,
                        'phone' => $parsedData['phone'] ?? null,
                        'location' => $parsedData['location'] ?? null,
                        'current_title' => $parsedData['current_title'] ?? null,
                        'total_experience_years' => $parsedData['total_experience_years'] ?? null,
                        'highest_education' => $parsedData['highest_education'] ?? null,
                        'source_type' => SourceType::UPLOAD_CV,
                        'profile_status' => ProfileStatus::NEW,

                    ];

                    $candidate = $this->candidateRepo->create($candidateData)['item'];
                }

                $application = $this->applicationRepo->getByCandidate($documentCvFile->vacancy_id, $candidate->id);

                if (!$application) {
                    $applicationData = [
                        'vacancy_id' => $documentCvFile->vacancy_id,
                        'candidate_id' => $candidate->id,
                        'owner_user_id' => $actorUserId,
                        'application_source' => Source::UPLOADED_CV,
                        'status' => Status::AI_ANALYZED,
                        'applied_at' => now(),
                        'ai_score' => $parsedData['overall_score'] ?? null,
                    ];
                    $application = $this->applicationRepo->create($applicationData)['item'];
                } else {
                    $this->applicationRepo->update($application, [
                        'status' => Status::AI_ANALYZED,
                        'ai_score' => $parsedData['overall_score'] ?? null,
                    ]);
                }

                $profile = $this->candidateProfileRepo->getByCandidateId($candidate->id);

                $profileData = [
                    'professional_summary' => $parsedData['professional_summary'] ?? null,
                    'skills_json' => $parsedData['skills_json'] ?? [],
                    'experience_json' => $parsedData['experience_json'] ?? [],
                    'education_json' => $parsedData['education_json'] ?? [],
                    'languages_json' => $parsedData['languages_json'] ?? [],
                    'certifications_json' => $parsedData['certifications_json'] ?? [],
                    'parsed_source' => ParsedSource::CV_PARSER,
                    'parser_version' => 'v1',
                    'last_parsed_at' => now(),
                    'candidate_id' => $candidate->id,
                    'cv_file_id' => $documentCvFile->id
                ];

                if ($profile) {
                    $this->candidateProfileRepo->update($profile, $profileData);
                } else {
                    $profile = $this->candidateProfileRepo->create($profileData)['item'];
                }

                $analysisData = [
                    'application_id' => $application->id,
                    'analysis_type' => 'cv_match',
                    'analysis_version' => 'v1',
                    'overall_score' => $parsedData['overall_score'] ?? null,
                    'skills_score' => $parsedData['skills_score'] ?? null,
                    'experience_score' => $parsedData['experience_score'] ?? null,
                    'education_score' => $parsedData['education_score'] ?? null,
                    'languages_score' => $parsedData['languages_score'] ?? null,
                    'pros_text' => $parsedData['pros_text'] ?? null,
                    'cons_text' => $parsedData['cons_text'] ?? null,
                    'matched_skills_json' => $parsedData['matched_skills_json'] ?? [],
                    'missing_skills_json' => $parsedData['missing_skills_json'] ?? [],
                    'critical_missing_json' => $parsedData['critical_missing_json'] ?? [],
                    'risk_flags_json' => $parsedData['risk_flags_json'] ?? [],
                    'seniority_fit' => $parsedData['seniority_fit'] ?? null,
                    'salary_fit' => $parsedData['salary_fit'] ?? null,
                    'requirement_matches_json' => $parsedData['requirement_matches_json'] ?? [],
                    'notes_json' => [
                        'parsed_source' => 'openai',
                        'document_type' => $openAIResponse['document_type'] ?? null,
                        'document_confidence' => $openAIResponse['document_confidence'] ?? null,
                        'expected_salary' => $parsedData['expected_salary'] ?? null,
                        'vacancy_min_salary' => $vacancy->min_salary,
                        'vacancy_max_salary' => $vacancy->max_salary,
                        'salary_fit' => $parsedData['salary_fit'] ?? null,
                        'overall_score_reasoning' => $parsedData['overall_score_reasoning'] ?? null,
                        'score_adjustment_notes_json' => $parsedData['score_adjustment_notes_json'] ?? [],
                    ],
                    'analyzed_at' => now(),
                ];

                $analysis = $this->aiAnalysisRepo->getByApplicationId($application->id);
                if (!$analysis)
                    $analysis = $this->aiAnalysisRepo->create($analysisData)['item'];
                else
                    $analysis = $this->aiAnalysisRepo->update($analysis, $analysisData)['item'];

                $this->candidateCvFileRepo->update($documentCvFile, [
                    'candidate_id' => $candidate->id,
                    'parse_status' => ParseStatus::PARSED,
                    'parsed_at' => now(),
                ]);

                $this->candidateRepo->update($candidate, [
                    'last_cv_file_id' => $documentCvFile->id,
                ]);

                $processedCount++;
            }

            if ($extension === 'zip') {
                $this->candidateCvFileRepo->update($cvFile, [
                    'parse_status' => $processedCount > 0 ? ParseStatus::PARSED : ParseStatus::FAILED,
                    'parsed_at' => $processedCount > 0 ? now() : null,
                ]);
            }

            DB::commit();

            if ($extension === 'zip' && $processedCount > 0) {
                $this->candidateCvFileRepo->softDelete($cvFile);
            }

            return [
                'cv_file' => $cvFile,
                'candidate' => $candidate,
                'application' => $application,
                'profile' => $profile,
                'analysis' => $analysis,
            ];
        } catch (\Throwable $ex) {
            DB::rollBack();

            if (isset($documentCvFile) && $documentCvFile) {
                $this->candidateCvFileRepo->update($documentCvFile, [
                    'parse_status' => ParseStatus::FAILED,
                ]);
            }

            Log::error('Analyze candidate cv service failed', [
                'file' => $ex->getFile(),
                'line' => $ex->getLine(),
                'message' => $ex->getMessage(),
            ]);

            throw $ex;
        } finally {
            $this->cleanupTempPaths($tempPaths);
        }
    }

    private function storeExtractedZipDocument(string $tempPath, string $extension): string
    {
        $directory = public_path('uploads/candidates');

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $fileName = random_int(10000000, 99999999) . '.' . strtolower($extension);
        $targetPath = $directory . DIRECTORY_SEPARATOR . $fileName;

        if (!File::copy($tempPath, $targetPath)) {
            throw new \RuntimeException('Extracted zip document could not be stored');
        }

        return 'uploads/candidates/' . $fileName;
    }

    private function cleanupTempPaths(array $paths): void
    {
        foreach ($paths as $path) {
            if ($path && file_exists($path)) {
                @unlink($path);
            }
        }
    }
}
