<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use App\User\Infrastructure\BcryptPasswordHasher;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Infrastructure\Repository\UserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            PasswordHasherInterface::class,
            BcryptPasswordHasher::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
