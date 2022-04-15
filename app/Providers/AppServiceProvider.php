<?php

namespace App\Providers;

use App\Repositories\CompanyRepository;
use App\Repositories\CompanyRepositoryInterface;
use App\Repositories\PasswordResetRepository;
use App\Repositories\PasswordResetRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, function() {
            return new UserRepository();
        });

        $this->app->bind(CompanyRepositoryInterface::class, function() {
            return new CompanyRepository();
        });

        $this->app->bind(PasswordResetRepositoryInterface::class, function() {
            return new PasswordResetRepository();
        });
    }
}
