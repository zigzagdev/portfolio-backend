<?php

namespace App\Providers;

use App\Post\Domain\RepositoryInterface\PostRepositoryInterface;
use App\Post\Infrastructure\Repository\PostRepository;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Infrastructure\Repository\UserRepository;
use App\User\Infrastructure\Service\JwtAuthService;
use Illuminate\Support\ServiceProvider;
use App\User\Infrastructure\Service\BcryptPasswordHasher;
use App\User\Domain\Service\GenerateTokenInterface;
use App\User\Infrastructure\Service\GenerateTokenService;
use App\User\Domain\Service\AuthServiceInterface;

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

        $this->app->bind(GenerateTokenInterface::class, function () {
            $secretKey = config('auth.jwt_secret');
            return new GenerateTokenService($secretKey);
        });

        $this->app->bind(
            AuthServiceInterface::class,
            JwtAuthService::class
        );

        $this->app->bind(
            PasswordHasherInterface::class,
            BcryptPasswordHasher::class
        );

        $this->app->bind(
            PostRepositoryInterface::class,
            PostRepository::class
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
