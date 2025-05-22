<?php

namespace App\User\Infrastructure\InfrastructureTest;

use App\Models\User;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserEntityFactory;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use App\User\Infrastructure\Repository\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class UserRepository_addUserTest extends TestCase
{
    use RefreshDatabase;

    private UserRepository $repository;
    private PasswordHasherInterface $hasher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hasher = $this->mockHasher();
        $this->repository = new UserRepository($this->hasher);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
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


        $expectedSkills = "\\\"Football\\\", \\\"Leadership\\\"";

        $this->assertDatabaseHas('users', [
            'first_name' => $this->mockRequest()['first_name'],
            'last_name' => $this->mockRequest()['last_name'],
            'email' => $this->mockRequest()['email'],
            'bio' => $this->mockRequest()['bio'],
            'location' => $this->mockRequest()['location'],
            'profile_image' => $this->mockRequest()['profile_image'],
        ], 'mysql');

        $user = User::where('email', 'real-madrid15@test.com')->first();
        $this->assertEquals(['Football', 'Leadership'], $user->skills);

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
        $hasher->shouldReceive('hash')
            ->with($this->mockRequest()['password'])
            ->andReturn($this->mockRequest()['password']);

        return $hasher;
    }

    private function mockEntity(): UserEntity
    {
        return UserEntityFactory::build($this->mockRequest(), $this->hasher);
    }
}