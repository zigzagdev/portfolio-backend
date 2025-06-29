<?php

namespace User\Infrastructure\InfrastructureTest\Service;

use App\Common\Domain\ValueObject\UserId;
use App\Models\User;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserEntityFactory;
use App\User\Domain\Factory\UserFromModelEntityFactory;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use App\User\Domain\ValueObject\AuthToken;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use App\User\Infrastructure\Repository\UserRepository;
use App\User\Infrastructure\Service\GenerateTokenService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class GenerateTokenServiceTest extends TestCase
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
        $this->secretKey = 'test-secret-key';
        $this->algorithm = 'HS256';
    }

    protected function tearDown(): void
    {
        parent::tearDown();
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

    private function refresh(): void
    {
        if (env('APP_ENV') === 'testing') {
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');
            User::truncate();
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    private function mockUserFromModelEntity(): UserEntity
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
            'alias' . UserEntityFactory::class
        );

        $entity = Mockery::mock(UserEntity::class);

        $factory
            ->shouldReceive('build')
            ->andReturn($entity);

        $entity
            ->shouldReceive('getUserId')
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

        return $entity;
    }


    /**
     * @test
     * @testdox GenerateTokenService_generateToken_successfully
     */
    public function test1(): void
    {
        $service = new GenerateTokenService($this->secretKey, $this->algorithm);

        $authToken = $service->generate(
            $this->mockEntity(),
        );

        $this->assertInstanceOf(AuthToken::class, $authToken);
    }

    /**
     * @test
     * @testdox GenerateTokenService_generateToken_with_invalid_credentials
     */
    public function test2(): void
    {
        $service = new GenerateTokenService($this->secretKey, $this->algorithm);

        $authToken = $service->generate(
            $this->mockEntity(),
        );

        $this->assertIsString($authToken->getValue());

        $decoded = (array) JWT::decode($authToken->getValue(), new Key($this->secretKey, $this->algorithm));

        $this->assertArrayHasKey('iat', $decoded);
        $this->assertArrayHasKey('exp', $decoded);
        $this->assertGreaterThan($decoded['iat'], $decoded['exp']);
    }
}