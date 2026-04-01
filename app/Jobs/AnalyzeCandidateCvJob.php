<?php

namespace App\Jobs;

use App\Services\AnalyzeCandidateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AnalyzeCandidateCvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    public function __construct(public int $cvFileId, public ?int $actorUserId = null)
    {
    }

    public function handle(AnalyzeCandidateService $analyzeCandidateService): void
    {
        $analyzeCandidateService->analyze($this->cvFileId, $this->actorUserId);
    }
}
