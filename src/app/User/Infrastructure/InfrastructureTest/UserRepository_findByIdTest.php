<?php

namespace App\User\Infrastructure\InfrastructureTest;

use App\User\Domain\Factory\UserEntityFactory;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserFromModelEntityFactory;
use App\User\Infrastructure\Repository\UserRepository;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Common\Domain\UserId;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use Mockery;

class UserRepository_findByIdTest extends TestCase
{
    private User $user;
    private UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->user = new User();
        $this->repository = new UserRepository(
            $this->mockHasher(),
            $this->user
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->refresh();
    }

    private function refresh(): void
    {
        if (env('APP_ENV') === 'testing') {
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');
            User::truncate();
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    private function mockEntity(): UserEntity
    {
        $factory  = Mockery::mock(
            'alias' . UserFromModelEntityFactory::class
        );

        $entity = Mockery::mock(UserEntity::class);

        $factory
            ->shouldReceive('buildFromModel')
            ->andReturn($entity);

        $entity
            ->shouldReceive('getId')
            ->andReturn(new UserId(1));

        $entity
            ->shouldReceive('getFirstName')
            ->andReturn($this->mockRequest()['first_name']);

        $entity
            ->shouldReceive('getLastName')
            ->andReturn($this->mockRequest()['last_name']);

        $entity
            ->shouldReceive('getPassword')
            ->andReturn(Password::fromHashed($this->mockRequest()['password']));

        $entity
            ->shouldReceive('getEmail')
            ->andReturn(new Email($this->mockRequest()['email']));

        $entity
            ->shouldReceive('getBio')
            ->andReturn($this->mockRequest()['bio']);

        $entity
            ->shouldReceive('getLocation')
            ->andReturn($this->mockRequest()['location']);

        $entity
            ->shouldReceive('getSkills')
            ->andReturn($this->mockRequest()['skills']);

        $entity
            ->shouldReceive('getProfileImage')
            ->andReturn($this->mockRequest()['profile_image']);

        return $entity;
    }

    private function mockHasher(): PasswordHasherInterface
    {
        $hasher = Mockery::mock(PasswordHasherInterface::class);
        $hasher->shouldReceive('hash')
            ->with($this->mockRequest()['password'])
            ->andReturn($this->mockRequest()['password']);

        return $hasher;
    }

    private function mockBeforeEntity(): UserEntity
    {
        return UserEntityFactory::build($this->mockRequest(), $this->hasher);
    }

    /**
     * @test
     * @testdox findById successfully (check instance type)
     */
    public function test1_find_user_type(): void
    {
        $this->repository->save($this->mockEntity());

        $result = $this->repository->findById(new UserId(1));

        $this->assertInstanceOf(UserEntity::class, $result);
    }

    public function test2_find_user_value_not_null(): void
    {
        $this->repository->save($this->mockEntity());

        $result = $this->repository->findById(new UserId(1));

        $this->assertSame($this->mockRequest()['first_name'], $result->getFirstName());
        $this->assertSame($this->mockRequest()['last_name'], $result->getLastName());
        $this->assertSame($this->mockRequest()['email'], $result->getEmail()->getValue());
        $this->assertSame($this->mockRequest()['bio'], $result->getBio());
        $this->assertSame($this->mockRequest()['location'], $result->getLocation());
        $this->assertSame($this->mockRequest()['skills'], $result->getSkills());
        $this->assertSame($this->mockRequest()['profile_image'], $result->getProfileImage());
    }

    private function mockRequest(): array
    {
        return [
            'first_name' => 'Sergio',
            'last_name' => 'Ramos',
            'email' => 'real-madrid15@test.com',
            'password' => 'el-capitán-1234',
            'bio' => 'Real Madrid player',
            'location' => 'Madrid',
            'skills' => ['Football', 'Leadership'],
            'profile_image' => 'https://example.com/sergio.jpg'
        ];
    }
}