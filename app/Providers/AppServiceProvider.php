<?php

namespace App\Providers;

use App\Repositories\Implementations\PermissionRepository;
use App\Repositories\Implementations\RoleRepository;
use App\Repositories\Implementations\UserRepository;
use App\Repositories\Interfaces\PermissionInterface;
use App\Repositories\Interfaces\RoleInterface;
use App\Repositories\Interfaces\UserInterface;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
