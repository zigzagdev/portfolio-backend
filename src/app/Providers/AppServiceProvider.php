<?php

namespace App\Providers;

use App\Post\Application\QueryServiceInterface\GetPostQueryServiceInterface;
use App\Post\Domain\RepositoryInterface\PostRepositoryInterface;
use App\Post\Infrastructure\Repository\PostRepository;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Domain\Service\PasswordResetGenerateTokenServiceInterface;
use App\User\Domain\Service\PasswordResetNotificationServiceInterface;
use App\User\Domain\Service\PasswordResetRequestLimitationServiceInterface;
use App\User\Domain\Service\ThrottlePasswordResetRequestServiceInterface;
use App\User\Infrastructure\Repository\UserRepository;
use App\User\Infrastructure\Service\JwtAuthService;
use App\User\Infrastructure\Service\PasswordResetGenerateTokenService;
use App\User\Infrastructure\Service\PasswordResetNotificationService;
use App\User\Infrastructure\Service\ThrottlePasswordResetRequestService;
use Illuminate\Support\ServiceProvider;
use App\User\Infrastructure\Service\BcryptPasswordHasher;
use App\User\Domain\Service\GenerateTokenInterface;
use App\User\Infrastructure\Service\GenerateTokenService;
use App\User\Domain\Service\AuthServiceInterface;
use App\Post\Infrastructure\QueryService\GetPostQueryService;

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

        $this->app->bind(
            GetPostQueryServiceInterface::class,
            GetPostQueryService::class
        );

        $this->app->bind(
            PasswordResetGenerateTokenServiceInterface::class,
            PasswordResetGenerateTokenService::class
        );

        $this->app->bind(
            PasswordResetNotificationServiceInterface::class,
            PasswordResetNotificationService::class
        );

        $this->app->bind(
            ThrottlePasswordResetRequestServiceInterface::class,
            ThrottlePasswordResetRequestService::class
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
