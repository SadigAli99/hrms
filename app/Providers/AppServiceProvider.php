<?php

namespace App\Providers;

use App\Repositories\Implementations\ApplicationRepository;
use App\Repositories\Implementations\AiAnalysisRepository;
use App\Repositories\Implementations\CandidateCvFileRepository;
use App\Repositories\Implementations\CandidateProfileRepository;
use App\Repositories\Implementations\CandidateRepository;
use App\Repositories\Implementations\DepartmentRepository;
use App\Repositories\Implementations\PermissionRepository;
use App\Repositories\Implementations\RoleRepository;
use App\Repositories\Implementations\TalentPoolRepository;
use App\Repositories\Implementations\UserRepository;
use App\Repositories\Implementations\VacancyRepository;
use App\Repositories\Interfaces\ApplicationInterface;
use App\Repositories\Interfaces\AiAnalysisInterface;
use App\Repositories\Interfaces\CandidateCvFileInterface;
use App\Repositories\Interfaces\CandidateInterface;
use App\Repositories\Interfaces\CandidateProfileInterface;
use App\Repositories\Interfaces\DepartmentInterface;
use App\Repositories\Interfaces\PermissionInterface;
use App\Repositories\Interfaces\RoleInterface;
use App\Repositories\Interfaces\TalentPoolInterface;
use App\Repositories\Interfaces\UserInterface;
use App\Repositories\Interfaces\VacancyInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(RoleInterface::class, RoleRepository::class);
        $this->app->bind(PermissionInterface::class, PermissionRepository::class);
        $this->app->bind(DepartmentInterface::class, DepartmentRepository::class);
        $this->app->bind(VacancyInterface::class, VacancyRepository::class);
        $this->app->bind(CandidateInterface::class, CandidateRepository::class);
        $this->app->bind(ApplicationInterface::class, ApplicationRepository::class);
        $this->app->bind(AiAnalysisInterface::class, AiAnalysisRepository::class);
        $this->app->bind(CandidateCvFileInterface::class, CandidateCvFileRepository::class);
        $this->app->bind(CandidateProfileInterface::class, CandidateProfileRepository::class);
        $this->app->bind(TalentPoolInterface::class, TalentPoolRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
