<?php

namespace App\User\Application\ApplicationTest;

use App\User\Application\UseCase\RegisterUserUseCase;
use App\User\Infrastructure\Repository\UserRepository;
use App\User\Domain\Factory\UserEntityFactory;
use App\User\Application\Dto\RegisterUserDto;
use App\User\Application\Factory\RegisterUserDtoFactory;
use App\User\Application\UseCommand\RegisterUserCommand;
use App\User\Application\Factory\RegisterUserCommandFactory;
use App\User\Domain\Entity\UserEntity;
use Tests\TestCase;
use Mockery;
use App\Common\Domain\UserId;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;

class RegisterUserUseCaseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockCommand(): RegisterUserCommand
    {
       $factory = Mockery::mock(
           'alias' . RegisterUserCommandFactory::class
       );

       $command = Mockery::mock(RegisterUserCommand::class);

       $factory
           ->shouldReceive('build')
           ->andReturn($command);

       $command
              ->shouldReceive('toArray')
              ->andReturn($this->arrayRequestData());

         return $command;
    }

    private function mockEntity(): UserEntity
    {
        $factory = Mockery::mock(
            'alias' . UserEntityFactory::class
        );

        $entity = Mockery::mock(UserEntity::class);

        $factory
            ->shouldReceive('build')
            ->andReturn($entity);

        $entity
            ->shouldReceive('getUserId')
            ->andReturn(new UserId($this->arrayRequestData()['id']));

        $entity
            ->shouldReceive('getFirstName')
            ->andReturn($this->arrayRequestData()['first_name']);

        $entity
            ->shouldReceive('getLastName')
            ->andReturn($this->arrayRequestData()['last_name']);

        $entity
            ->shouldReceive('getEmail')
            ->andReturn(new Email($this->arrayRequestData()['email']));

        $entity
            ->shouldReceive('getBio')
            ->andReturn($this->arrayRequestData()['bio']);

        $entity
            ->shouldReceive('getLocation')
            ->andReturn($this->arrayRequestData()['location']);

        $entity
            ->shouldReceive('getSkills')
            ->andReturn($this->arrayRequestData()['skills']);

        $entity
            ->shouldReceive('getProfileImage')
            ->andReturn($this->arrayRequestData()['profile_image']);

        return $entity;
    }

    private function mockDto(): RegisterUserDto
    {
        $factory = Mockery::mock(
            'alias' . RegisterUserDtoFactory::class
        );

        $dto = Mockery::mock(RegisterUserDto::class);

        $factory
            ->shouldReceive('build')
            ->andReturn($dto);

        $dto
            ->shouldReceive('toArray')
            ->andReturn($this->arrayRequestData());

        return $dto;
    }

    private function mockRepository(): UserRepository
    {
        $repository = Mockery::mock(UserRepository::class);

        $repository
            ->shouldReceive('save')
            ->andReturn($this->mockEntity());

        $repository
            ->shouldReceive('existsByEmail')
            ->never();

        $repository
            ->shouldReceive('findById')
            ->never();

        return $repository;
    }

    private function mockPasswordHasher(): PasswordHasherInterface
    {
        $hasher = Mockery::mock(PasswordHasherInterface::class);

        $hasher
            ->shouldReceive('hash')
            ->andReturn($this->arrayRequestData()['password']);

        return $hasher;
    }


    private function arrayRequestData(): array
    {
       return [
           'id' => 1,
           'first_name' => 'Cristiano',
           'last_name' => 'Ronaldo',
           'email' => 'cristiano-ronaldo@test.com',
           'password' => 'test1234',
           'bio' => 'I am a football player',
           'location' => 'Manchester',
           'skills' => ['Laravel', 'React'],
           'profile_image' => 'https://example.com/profile.jpg',
       ];
    }

    /**
     * @test
     * @testdox RegisterUserUseCaseTest_build_successfully check type
     */
    public function test(): void
    {
        $usecase = new RegisterUserUseCase(
            $this->mockRepository(),
            $this->mockPasswordHasher()
        );

        $result = $usecase->handle(
            $this->mockCommand(),
        );

        $this->assertInstanceOf(RegisterUserDto::class, $result);
    }
}