<?php

namespace App\Providers;

use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Infrastructure\Repository\UserRepository;
use Illuminate\Support\ServiceProvider;
use User\Infrastructure\Service\BcryptPasswordHasher;
use App\User\Domain\Service\GenerateTokenInterface;
use User\Infrastructure\Service\GenerateTokenService;

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

//        $this->app->bind(GenerateTokenInterface::class, function () {
//            return new GenerateTokenService(
//                config('auth.jwt_secret'),
//                'HS256'
//            );
//        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
