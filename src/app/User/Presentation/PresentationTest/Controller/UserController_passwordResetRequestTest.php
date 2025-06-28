<?php

namespace App\User\Presentation\PresentationTest\Controller;

use App\Common\Domain\ValueObject\UserId;
use App\User\Application\UseCase\RequestUserPasswordResetUseCase;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserFromModelEntityFactory;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Domain\Service\PasswordResetGenerateTokenServiceInterface;
use App\User\Domain\Service\PasswordResetNotificationServiceInterface;
use App\User\Domain\Service\ThrottlePasswordResetRequestServiceInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\PasswordResetToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;
use App\User\Presentation\Controller\UserController;

class UserController_passwordResetRequestTest extends TestCase
{

    private $controller;
    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new UserController();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockUseCase(): RequestUserPasswordResetUseCase
    {
        $useCase = Mockery::mock(RequestUserPasswordResetUseCase::class);

        $useCase
            ->shouldReceive('handle')
            ->with(Mockery::type('string'))
            ->andReturn(true);

        return $useCase;
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

    private function mockRequest(): Request
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('input')
            ->with('email')
            ->andReturn($this->arrayData()['email']);

        return $request;
    }

    public function test_controller(): void
    {
        $result = $this->controller->passwordResetRequest(
            $this->mockRequest(),
            $this->mockUseCase()
        );

        $this->assertInstanceOf(JsonResponse::class, $result);
    }
}