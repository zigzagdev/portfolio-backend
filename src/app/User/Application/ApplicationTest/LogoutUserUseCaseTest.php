<?php

namespace App\User\Application\ApplicationTest;

use App\Common\Domain\UserId;
use Tests\TestCase;
use App\User\Application\UseCase\LogoutUserUseCase;
use App\User\Domain\Service\AuthServiceInterface;
use App\User\Domain\Entity\UserEntity;
use Mockery;
use App\User\Domain\Factory\UserEntityFactory;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;

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
        $useCase = new LogoutUserUseCase($authService);

        $useCase->handle($this->mockEntity());

        $this->assertInstanceOf(LogoutUserUseCase::class, $useCase);
    }

    private function mockEntity(): UserEntity
    {
        $factory = Mockery::mock(
            'alias' . UserEntityFactory::class
        );

        $entity = Mockery::mock(UserEntity::class);

        $factory
            ->shouldReceive('build')
            ->with($this->arrayData())
            ->with($this->mockPasswordHasher())
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