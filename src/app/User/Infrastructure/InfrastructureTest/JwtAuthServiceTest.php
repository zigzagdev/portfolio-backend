<?php

namespace App\User\Infrastructure\InfrastructureTest;

use App\Common\Domain\UserId;
use App\Models\User;
use App\User\Domain\Factory\UserFromModelEntityFactory;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use App\User\Infrastructure\Repository\UserRepository;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\User\Domain\Service\AuthServiceInterface;
use Mockery;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use App\User\Infrastructure\Service\JwtAuthService;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;

class JwtAuthServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->repository = new UserRepository(
            $this->mockHasher(),
            new User()
        );
        $this->user = $this->initialInsert();
    }

    protected function tearDown(): void
    {
        $this->refresh();
        parent::tearDown();
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

        $hasher
            ->shouldReceive('hash')
            ->andReturnUsing(fn($plain) => $plain);

        $hasher
            ->shouldReceive('hash')
            ->with($this->mockRequest()['password'])
            ->andReturn($this->mockRequest()['password']);

        return $hasher;
    }

    private function mockRequest(): array
    {
        return [
            'first_name' => 'Toni',
            'last_name' => 'Kroos',
            'email' => 'real-madrid6@test.com',
            'password' => 'el-capitán-1234',
            'bio' => 'Real Madrid player',
            'location' => 'Madrid',
            'skills' => ['Football', 'Leadership'],
            'profile_image' => 'https://example.com/sergio.jpg'
        ];
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

        $hashed = bcrypt($this->mockRequest()['password']);
        $entity
            ->shouldReceive('getPassword')
            ->andReturn(Password::fromHashed($hashed));

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

    /**
     * @test
     * @testdox authenticate successfully (check instance type)
     */
    public function test1(): void
    {
        $email = new Email($this->mockRequest()['email']);
        $password = Password::fromPlainText($this->mockRequest()['password'], $this->mockHasher());

        $repository = Mockery::mock(UserRepositoryInterface::class);
        $repository
            ->shouldReceive('existsByEmail')
            ->with($email)
            ->andReturn(true);
        $repository
            ->shouldReceive('findByEmail')
            ->with($email)
            ->andReturn($this->mockEntity());

        $authService = new JwtAuthService(
            $repository,
            $this->mockHasher()
        );

        $result = $authService->attemptLogin($email, $password);

        $this->assertInstanceOf(UserEntity::class, $result);
    }

    /**
     * @test
     * @testdox authenticate successfully (check if the user is authenticated)
     */
    public function test2(): void
    {
        $email = new Email($this->mockRequest()['email']);
        $password = Password::fromPlainText($this->mockRequest()['password'], $this->mockHasher());

        $repository = Mockery::mock(UserRepositoryInterface::class);
        $repository
            ->shouldReceive('existsByEmail')
            ->with($email)
            ->andReturn(true);
        $repository
            ->shouldReceive('findByEmail')
            ->with($email)
            ->andReturn($this->mockEntity());

        $authService = new JwtAuthService(
            $repository,
            $this->mockHasher()
        );

        $result = $authService->attemptLogin($email, $password);

        $this->assertTrue($result instanceof UserEntity);
    }

    /**
     * @test
     * @testdox authenticate with wrong password (check if the user is null)
     */
    public function test3(): void
    {
        $email = new Email($this->mockRequest()['email']);
        $password = Password::fromPlainText('wrong-password', $this->mockHasher());

        $repository = Mockery::mock(UserRepositoryInterface::class);
        $repository
            ->shouldReceive('existsByEmail')
            ->with($email)
            ->andReturn(true);
        $repository
            ->shouldReceive('findByEmail')
            ->with($email)
            ->andReturn($this->mockEntity());

        $authService = new JwtAuthService(
            $repository,
            $this->mockHasher()
        );

        $result = $authService->attemptLogin($email, $password);

        $this->assertNull($result);
    }

    /**
     * @test
     * @testdox authenticate with non-existing email (check if the user is null)
     */
    public function test4(): void
    {
        $email = new Email('wrong-test1234@test.com');
        $password = Password::fromPlainText($this->mockRequest()['password'], $this->mockHasher());

        $repository = Mockery::mock(UserRepositoryInterface::class);

        $repository
            ->shouldReceive('existsByEmail')
            ->with($email)
            ->andReturn(false);

        $repository
            ->shouldReceive('findByEmail')
            ->with($email)
            ->andReturn(null);

        $authService = new JwtAuthService(
            $repository,
            $this->mockHasher()
        );

        $result = $authService->attemptLogin($email, $password);

        $this->assertNull($result);
    }
}