<?php

namespace App\Providers;

use App\Repositories\Implementations\DepartmentRepository;
use App\Repositories\Implementations\PermissionRepository;
use App\Repositories\Implementations\RoleRepository;
use App\Repositories\Implementations\UserRepository;
use App\Repositories\Implementations\VacancyRepository;
use App\Repositories\Interfaces\DepartmentInterface;
use App\Repositories\Interfaces\PermissionInterface;
use App\Repositories\Interfaces\RoleInterface;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
