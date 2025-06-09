<?php

namespace App\User\Application\ApplicationTest;

use App\Models\User;
use App\User\Application\Dto\ShowUserDto;
use App\User\Application\UseCase\ShowUserUseCase;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserFromModelEntityFactory;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use Common\Domain\ValueObjet\UserId;
use Mockery;
use Tests\TestCase;

class ShowUserUseCaseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
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
            ->andReturn(new UserId($this->requestData()['id']));

        $entity
            ->shouldReceive('getFirstName')
            ->andReturn($this->requestData()['first_name']);

        $entity
            ->shouldReceive('getLastName')
            ->andReturn($this->requestData()['last_name']);

        $entity
            ->shouldReceive('getEmail')
            ->andReturn(new Email($this->requestData()['email']));

        $entity
            ->shouldReceive('getBio')
            ->andReturn($this->requestData()['bio']);

        $entity
            ->shouldReceive('getLocation')
            ->andReturn($this->requestData()['location']);

        $entity
            ->shouldReceive('getSkills')
            ->andReturn(json_decode($this->requestData()['skills'], true));

        $entity
            ->shouldReceive('getProfileImage')
            ->andReturn($this->requestData()['profile_image']);

        return $entity;
    }

    private function mockRepository()
    {
        $repository = Mockery::mock(UserRepositoryInterface::class);

        $repository
            ->shouldReceive('findById')
            ->with(new Userid($this->requestData()['id']))
            ->andReturn($this->mockUserEntity());

        return $repository;
    }

    private function mockDto(): ShowUserDto
    {
        $dto = Mockery::mock(ShowUserDto::class);

        $dto
            ->shouldReceive('build')
            ->with($this->mockUserEntity())
            ->andReturn($dto);

        return $dto;
    }

    private function requestData(): array
    {
        return [
            'id' => 1,
            'first_name' => 'Andres',
            'last_name' => 'Iniesta',
            'bio' => 'Soccer player',
            'email' => 'barcelona8@test.com',
            'location' => 'Spain',
            'skills' => json_encode(['dribbling', 'passing']),
            'profile_image' => 'https://example.com/profile.jpg',
        ];
    }

    private function mockUser()
    {
        $user = Mockery::mock(User::class);

        return $user;
    }

    /**
     * @test
     * @testdox ShowUserUseCaseTest_build_successfully check type
     */
    public function test1(): void
    {
        $useCase = new ShowUserUseCase(
            $this->mockRepository(),
        );

        $result = $useCase->handle(
            $this->requestData()['id']
        );

        $this->assertInstanceOf(ShowUserDto::class, $result);
    }

    /**
     * @test
     * @testdox ShowUserUseCaseTest_build_successfully check value
     */
    public function test2(): void
    {
        $useCase = new ShowUserUseCase(
            $this->mockRepository(),
        );

        $result = $useCase->handle(
            $this->requestData()['id']
        );

        foreach ($this->requestData() as $key => $expectedValue) {
            $camelKey = lcfirst(str_replace('_', '', ucwords($key, '_')));
            $getter = 'get' . ucfirst($camelKey);

            $this->assertEquals($expectedValue, $result->$getter());
        }
    }
}