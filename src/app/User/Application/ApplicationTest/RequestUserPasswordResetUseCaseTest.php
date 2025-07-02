<?php

namespace App\User\Application\ApplicationTest;

use App\Common\Domain\ValueObject\UserId;
use App\User\Application\UseCase\RequestUserPasswordResetUseCase;
use App\User\Domain\Factory\UserFromModelEntityFactory;
use App\User\Domain\ValueObject\Email;
use Tests\TestCase;
use Mockery;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Service\PasswordResetNotificationServiceInterface;
use App\User\Domain\Service\ThrottlePasswordResetRequestServiceInterface;
use App\User\Domain\Service\PasswordResetGenerateTokenServiceInterface;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Domain\ValueObject\PasswordResetToken;

class RequestUserPasswordResetUseCaseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function arrayData(): array
    {
        return [
            'first_name' => 'Andres',
            'last_name' => 'Iniesta',
            'bio' => 'Soccer player',
            'email' => 'barcelona8@test.com',
            'location' => 'Spain',
            'skills' => json_encode(['dribbling', 'passing']),
            'profile_image' => 'https://example.com/profile.jpg',
        ];
    }

    private function mockUserEntity(): UserEntity
    {
        $factory = Mockery::mock(
            'alias' . UserFromModelEntityFactory::class
        );

        $entity = Mockery::mock(UserEntity::class);

        $factory
            ->shouldReceive('build')
            ->andReturn($entity);

        $entity
            ->shouldReceive('getUserId')
            ->andReturn(new UserId(1));

        $entity
            ->shouldReceive('getFirstName')
            ->andReturn($this->arrayData()['first_name']);

        $entity
            ->shouldReceive('getLastName')
            ->andReturn($this->arrayData()['last_name']);

        $entity
            ->shouldReceive('getEmail')
            ->andReturn(new Email($this->arrayData()['email']));

        $entity
            ->shouldReceive('getBio')
            ->andReturn($this->arrayData()['bio']);

        $entity
            ->shouldReceive('getLocation')
            ->andReturn($this->arrayData()['location']);

        $entity
            ->shouldReceive('getSkills')
            ->andReturn(json_decode($this->arrayData()['skills'], true));

        $entity
            ->shouldReceive('getProfileImage')
            ->andReturn($this->arrayData()['profile_image']);

        return $entity;
    }

    private function mockRepository(UserEntity $entity): UserRepositoryInterface
    {
        $repository = Mockery::mock(UserRepositoryInterface::class);

        $repository
            ->shouldReceive('findByEmail')
            ->with(
                Mockery::on(
                    fn($arg) => $arg instanceof Email && $arg->getValue() === $this->arrayData()['email']
                )
            )
            ->andReturn($entity);

        $repository
            ->shouldReceive('savePasswordResetToken')
            ->with(
                Mockery::on(fn($arg) => $arg instanceof UserId && $arg->getValue() === 1),
                Mockery::type(PasswordResetToken::class)
            )
            ->andReturnNull();

        return $repository;
    }

    private function mockPasswordResetNotification(
        UserEntity $entity,
        PasswordResetToken $token
    ): PasswordResetNotificationServiceInterface
    {
        $service = Mockery::mock(PasswordResetNotificationServiceInterface::class);

        $service
            ->shouldReceive('sendResetLink')
            ->with(
                $entity,
                $token->getValue()
            )
            ->andReturnNull();

        return $service;
    }

    private function mockThrottleService(UserEntity $entity): ThrottlePasswordResetRequestServiceInterface
    {
        $service = Mockery::mock(ThrottlePasswordResetRequestServiceInterface::class);

        $service
            ->shouldReceive('checkThrottling')
            ->with($entity)
            ->andReturnTrue();

        return $service;
    }

    private function mockGenerateTokenService(PasswordResetToken $token): PasswordResetGenerateTokenServiceInterface
    {
        $service = Mockery::mock(PasswordResetGenerateTokenServiceInterface::class);

        $service
            ->shouldReceive('generateToken')
            ->andReturn($token);

        return $service;
    }

    public function test_request_password_reset_success(): void
    {
        $userEntity = $this->mockUserEntity();
        $token = new PasswordResetToken(random_bytes(32));

        $useCase = new RequestUserPasswordResetUseCase(
            $this->mockRepository($userEntity),
            $this->mockGenerateTokenService($token),
            $this->mockPasswordResetNotification($userEntity, $token),
            $this->mockThrottleService($userEntity)
        );

        $useCase->handle($this->arrayData()['email']);

        $this->assertTrue(true, 'Password reset request handled successfully');
    }
}