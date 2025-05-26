<?php

namespace App\User\Application\UseCase;

use App\Common\Domain\UserId;
use App\User\Domain\Factory\UserFromModelEntityFactory;
use App\User\Domain\Factory\UserUpdateEntityFactory;
use App\User\Domain\ValueObject\Email;
use Tests\TestCase;
use Mockery;
use App\User\Application\UseCommand\UpdateUserCommand;
use App\User\Application\Dto\UpdateUserDto;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Domain\Entity\UserEntity;

class UpdateUserUseCaseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockUseCommand(): UpdateUserCommand
    {
        $command = Mockery::mock(UpdateUserCommand::class);

        $command
            ->shouldReceive('getId')
            ->andReturn($this->arrayData()['id']);

        $command
            ->shouldReceive('getFirstName')
            ->andReturn($this->arrayData()['first_name']);

        $command
            ->shouldReceive('getLastName')
            ->andReturn($this->arrayData()['last_name']);

        $command
            ->shouldReceive('getEmail')
            ->andReturn($this->arrayData()['email']);

        $command
            ->shouldReceive('getBio')
            ->andReturn($this->arrayData()['bio']);

        $command
            ->shouldReceive('getLocation')
            ->andReturn($this->arrayData()['location']);

        $command
            ->shouldReceive('getSkills')
            ->andReturn($this->arrayData()['skills']);

        $command
            ->shouldReceive('getProfileImage')
            ->andReturn($this->arrayData()['profile_image']);

        $command
            ->shouldReceive('toArray')
            ->andReturn($this->arrayData());

        return $command;
    }

    private function mockUserUpdateEntityFactory(): void
    {
        $factory = Mockery::mock('alias:' . UserUpdateEntityFactory::class);

        $factory
            ->shouldReceive('build')
            ->with(Mockery::on(function ($arg) {
                return $arg instanceof UpdateUserCommand;
            }))
            ->andReturn($this->mockEntity());
    }

    private function mockModelEntityFactory(): void
    {
        $factory = Mockery::mock('alias:' . UserFromModelEntityFactory::class);

        $factory
            ->shouldReceive('buildFromModel')
            ->with(Mockery::on(function ($arg) {
                return $arg instanceof UserEntity;
            }))
            ->andReturn($this->mockEntity());
    }

    private function mockEntity(): UserEntity
    {
        $factory = Mockery::mock('alias:' . UserUpdateEntityFactory::class);

        $entity = Mockery::mock(UserEntity::class);

        $factory
            ->shouldReceive('build')
            ->with(Mockery::on(function ($arg) {
                return $arg instanceof UpdateUserCommand;
            }))
            ->andReturn(Mockery::on(UserEntity::class));

        $entity
            ->shouldReceive('getUserId')
            ->andReturn(new UserId($this->arrayData()['id']));

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
            ->andReturn($this->arrayData()['skills']);

        $entity
            ->shouldReceive('getProfileImage')
            ->andReturn($this->arrayData()['profile_image']);

        return $entity;
    }

    private function mockDto(): UpdateUserDto
    {
        $dto = Mockery::mock(UpdateUserDto::class);

        $dto
            ->shouldReceive('getId')
            ->andReturn($this->arrayData()['id']);

        $dto
            ->shouldReceive('getFirstName')
            ->andReturn($this->arrayData()['first_name']);

        $dto
            ->shouldReceive('getLastName')
            ->andReturn($this->arrayData()['last_name']);

        $dto
            ->shouldReceive('getEmail')
            ->andReturn($this->arrayData()['email']);

        $dto
            ->shouldReceive('getBio')
            ->andReturn($this->arrayData()['bio']);

        $dto
            ->shouldReceive('getLocation')
            ->andReturn($this->arrayData()['location']);

        $dto
            ->shouldReceive('getSkills')
            ->andReturn($this->arrayData()['skills']);

        $dto
            ->shouldReceive('getProfileImage')
            ->andReturn($this->arrayData()['profile_image']);

        return $dto;
    }

    private function mockRepository()
    {
        $repository = Mockery::mock(UserRepositoryInterface::class);

        $repository
            ->shouldReceive('update')
            ->with(Mockery::on(function ($arg) {
                return $arg instanceof UserEntity;
            }))
            ->andReturn($this->mockEntity());

        return $repository;
    }

    private function arrayData(): array
    {
        return [
            'id' => 1,
            'first_name' => 'Cristiano',
            'last_name' => 'Ronaldo',
            'bio' => 'I am a football player',
            'email' => 'manchester-united7@test.com',
            'location' => null,
            'skills' => [],
            'profile_image' => 'https://example.com/profile.jpg',
        ];
    }

    /**
     * @test
     */
    public function test1(): void
    {
        $this->mockUserUpdateEntityFactory();
        $this->mockModelEntityFactory();

        $useCase = new UpdateUseCase(
            $this->mockRepository(),
        );

        $result = $useCase->handle(
            $this->mockUseCommand(),
        );

        $this->assertInstanceOf(UpdateUserDto::class, $result);
    }

    /**
     * @test
     * @testdox UpdateUserUseCaseTest_update_user_successfully
     */
    public function test2(): void
    {
        $this->mockUserUpdateEntityFactory();
        $this->mockModelEntityFactory();

        $useCase = new UpdateUseCase(
            $this->mockRepository(),
        );

        $result = $useCase->handle(
            $this->mockUseCommand(),
        );

        $this->assertEquals($result->getId(), new UserId($this->arrayData()['id']));
        $this->assertEquals($result->getFirstName(), $this->arrayData()['first_name']);
        $this->assertEquals($result->getLastName(), $this->arrayData()['last_name']);
        $this->assertEquals($result->getEmail(), new Email($this->arrayData()['email']));
        $this->assertEquals($result->getBio(), $this->arrayData()['bio']);
        $this->assertEquals($result->getLocation(), $this->arrayData()['location']);
        $this->assertEquals($result->getSkills(), $this->arrayData()['skills']);
        $this->assertEquals($result->getProfileImage(), $this->arrayData()['profile_image']);
    }
}