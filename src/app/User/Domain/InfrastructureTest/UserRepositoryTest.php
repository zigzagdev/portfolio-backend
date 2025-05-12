<?php

namespace App\User\Domain\InfrastructureTest;

use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserEntityFactory;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use App\User\Infrastructure\Repository\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;
    private UserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new UserRepository();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     * @testdox register successfully (check instance type)
     */
    public function test1(): void
    {
        $result = $this->repository->save($this->mockEntity());

        $this->assertInstanceOf(UserEntity::class, $result);
    }

    /**
     * @test
     * @testdox register successfully (check if the entity is saved)
     */
    public function test2(): void
    {
        $this->repository->save($this->mockEntity());

        $this->assertDatabaseHas('users', [
            'first_name' => $this->mockRequest()['first_name'],
            'last_name' => $this->mockRequest()['last_name'],
            'email' => $this->mockRequest()['email'],
            'bio' => $this->mockRequest()['bio'],
            'location' => $this->mockRequest()['location'],
            'skills' => json_encode($this->mockRequest()['skills'], JSON_UNESCAPED_UNICODE),
            'profile_image' => $this->mockRequest()['profile_image'],
        ], 'mysql');
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

    private function mockHasher(): PasswordHasherInterface
    {
        $hasher = Mockery::mock(PasswordHasherInterface::class);

        $hasher
            ->shouldReceive('hash')
            ->with($this->mockRequest()['password'])
            ->andReturn($this->mockRequest()['password']);

        return $hasher;
    }

    private function mockEntity(): UserEntity
    {
        return UserEntityFactory::build($this->mockRequest(), $this->mockHasher());
    }
}