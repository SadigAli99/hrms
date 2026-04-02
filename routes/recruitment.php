<?php

use App\Http\Controllers\Recruitment\CandidateController;
use App\Http\Controllers\Recruitment\DepartmentController;
use App\Http\Controllers\Recruitment\TalentPoolController;
use App\Http\Controllers\Recruitment\VacancyController;
use Illuminate\Support\Facades\Route;

Route::get('department/filter', [DepartmentController::class, 'filter'])->name('department.filter');
Route::resource('department', DepartmentController::class);

Route::get('vacancy/filter', [VacancyController::class, 'filter'])->name('vacancy.filter');
Route::resource('vacancy', VacancyController::class);
Route::get('vacancy/{id}/candidates', [CandidateController::class, 'index'])->name('vacancy.candidates');
Route::post('vacancy/{id}/upload-cv', [CandidateController::class, 'upload_cv'])->name('vacancy.upload-cv');

Route::delete('candidate/{id}/delete-cv', [CandidateController::class, 'delete_cv'])->name('candidate.delete-cv');
Route::post('candidate/analyze-cv', [CandidateController::class, 'analyze_cv'])->name('candidate.analyze-cv');
Route::post('candidate/retry-parse', [CandidateController::class, 'retry_parse'])->name('candidate.retry-parse');
Route::post('candidate/bulk-analyze', [CandidateController::class, 'bulk_analyze'])->name('candidate.bulk-analyze');
Route::post('candidate/talent-pool', [CandidateController::class, 'talent_pool'])->name('candidate.talent-pool');

Route::get('talent-pool/filter', [TalentPoolController::class, 'filter'])->name('talent-pool.filter');
Route::get('talent-pool', [TalentPoolController::class, 'index'])->name('talent-pool.index');
Route::post('talent-pool/{id}/add-to-vacancy', [TalentPoolController::class, 'add_to_vacancy'])->name('talent-pool.add-to-vacancy');
