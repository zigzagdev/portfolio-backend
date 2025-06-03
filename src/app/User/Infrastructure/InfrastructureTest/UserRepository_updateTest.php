<?php

namespace App\User\Infrastructure\InfrastructureTest;

use App\Models\User;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserUpdateEntityFactory;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Infrastructure\Repository\UserRepository;
use Common\Domain\ValueObject\UserId;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class UserRepository_updateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->user = $this->initialInsert();
        $this->repository = new UserRepository(
            $this->mockHasher(),
            new User()
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

    private function mockHasher(): PasswordHasherInterface
    {
        $hasher = Mockery::mock(PasswordHasherInterface::class);

        $hasher->shouldReceive('hash')
            ->with($this->user->password)
            ->andReturn($this->user->password);

        return $hasher;
    }

    private function initialInsert(): User
    {
        $user = User::create([
            'first_name' => 'Toni',
            'last_name' => 'Kroos',
            'email' => 'real-madrid6@test.com',
            'password' => 'el-capitán-1234',
            'bio' => 'Real Madrid player',
            'location' => 'Madrid',
            'skills' => ['Football', 'Leadership'],
            'profile_image' => 'https://example.com/sergio.jpg'
        ]);

        return $user;
    }

    private function updateData(): array
    {
        return [
            'id' => $this->user->id,
            'first_name' => 'Sergio',
            'last_name' => 'Aguero',
            'email' => 'man-city10@test.com',
            'bio' => null,
            'location' => null,
            'skills' => ['Football', 'Leadership', 'Laravel'],
            'profile_image' => 'https://example.com/sergio.jpg'
        ];
    }

    private function mockEntity(): UserEntity
    {
        $factory = Mockery::mock(
            'alias' . UserUpdateEntityFactory::class
        );

        $entity = Mockery::mock(UserEntity::class);

        $factory
            ->shouldReceive('build')
            ->andReturn($entity);

        $entity
            ->shouldReceive('getUserId')
            ->andReturn(new UserId($this->user->id));

        $entity
            ->shouldReceive('getFirstName')
            ->andReturn($this->updateData()['first_name']);

        $entity
            ->shouldReceive('getLastName')
            ->andReturn($this->updateData()['last_name']);

        $entity
            ->shouldReceive('getEmail')
            ->andReturn(new Email($this->updateData()['email']));

        $entity
            ->shouldReceive('getBio')
            ->andReturn($this->updateData()['bio']);

        $entity
            ->shouldReceive('getLocation')
            ->andReturn($this->updateData()['location']);

        $entity
            ->shouldReceive('getSkills')
            ->andReturn($this->updateData()['skills']);

        $entity
            ->shouldReceive('getProfileImage')
            ->andReturn($this->updateData()['profile_image']);

        return $entity;
    }

    /**
     * @test
     * @testdox UserRepository_update_successfully
     */
    public function test1(): void
    {
        $result = $this->repository->update(
            $this->mockEntity()
        );

        $this->assertInstanceOf(UserEntity::class, $result);
    }

    /**
     * @test
     * @testdox UserRepository_update_successfully check value
     */
    public function test2(): void
    {
        $result = $this->repository->update(
            $this->mockEntity()
        );

        $this->assertEquals($result->getFirstName(), $this->updateData()['first_name']);
        $this->assertEquals($result->getLastName(), $this->updateData()['last_name']);
        $this->assertEquals($result->getEmail()->getValue(), $this->updateData()['email']);
        $this->assertEquals($result->getBio(), $this->updateData()['bio']);
        $this->assertEquals($result->getLocation(), $this->updateData()['location']);
        $this->assertEquals($result->getSkills(), $this->updateData()['skills']);
        $this->assertEquals($result->getProfileImage(), $this->updateData()['profile_image']);
    }
}