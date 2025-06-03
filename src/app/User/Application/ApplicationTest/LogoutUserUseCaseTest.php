<?php

namespace App\User\Application\ApplicationTest;

use App\User\Application\UseCase\LogoutUserUseCase;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserEntityFactory;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Domain\Service\AuthServiceInterface;
use Common\Domain\ValueObject\UserId;
use Mockery;
use Tests\TestCase;

class LogoutUserUseCaseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_succeeded(): void
    {
        $authService = $this->mockAuthService();
        $repository = $this->mockRepository();
        $useCase = new LogoutUserUseCase(
            $authService,
            $repository
        );

        $useCase->handle($this->arrayData()['id']);

        $this->assertInstanceOf(LogoutUserUseCase::class, $useCase);
    }

    private function mockEntity(): UserEntity
    {
        $factory = Mockery::mock(
            'alias:' . UserEntityFactory::class
        );

        $entity = Mockery::mock(UserEntity::class);

        $factory
            ->shouldReceive('build')
            ->with($this->arrayData(), $this->mockPasswordHasher())
            ->andReturn($entity);

        $entity
            ->shouldReceive('getId')
            ->andReturn(new UserId($this->arrayData()['id']));

        $entity
            ->shouldReceive('getEmail')
            ->andReturn($this->arrayData()['email']);

        $entity
            ->shouldReceive('getPassword')
            ->andReturn($this->arrayData()['password']);

        $entity
            ->shouldReceive('getFirstName')
            ->andReturn($this->arrayData()['first_name']);

        $entity
            ->shouldReceive('getLastName')
            ->andReturn($this->arrayData()['last_name']);

        $entity
            ->shouldReceive('getBio')
            ->andReturn($this->arrayData()['bio']);

        $entity
            ->shouldReceive('getLocation')
            ->andReturn($this->arrayData()['location']);

        $entity
            ->shouldReceive('getSkills')
            ->andReturn($this->arrayData()['skills']);

        $entity
            ->shouldReceive('getProfileImage')
            ->andReturn($this->arrayData()['profile_image']);

        return $entity;
    }

    private function mockRepository(): UserRepositoryInterface
    {
        $interface = Mockery::mock(
            UserRepositoryInterface::class
        );

        $interface
            ->shouldReceive('findById')
            ->with(Mockery::type(UserId::class))
            ->andReturn($this->mockEntity());

        return $interface;
    }

    private function mockPasswordHasher(): PasswordHasherInterface
    {
        $hasher = Mockery::mock(PasswordHasherInterface::class);

        $hasher
            ->shouldReceive('hash')
            ->andReturn($this->arrayData()['password']);

        return $hasher;
    }

    private function arrayData(): array
    {
        return [
            'id' => 1,
            'first_name' => 'Sergio',
            'last_name' => 'Ramos',
            'email' => 'real-madrid4@test.com',
            'password' => 'el-capitán-1234',
            'bio' => 'Real Madrid player',
            'location' => 'Madrid',
            'skills' => ['Football', 'Leadership'],
            'profile_image' => 'https://example.com/sergio.jpg',
        ];
    }

    private function mockAuthService(): AuthServiceInterface
    {
        $authService = Mockery::mock(
            AuthServiceInterface::class
        );

        $authService
            ->shouldReceive('attemptLogout')
            ->with(Mockery::type(UserEntity::class))
            ->andReturn(true);

        return $authService;
    }
}