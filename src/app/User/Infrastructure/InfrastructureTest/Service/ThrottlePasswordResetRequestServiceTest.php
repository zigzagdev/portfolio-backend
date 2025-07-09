<?php

namespace App\User\Infrastructure\InfrastructureTest\Service;

use App\Common\Domain\ValueObject\UserId;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserEntityFactory;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\User;
use Mockery;
use App\Models\PasswordResetRequest;
use App\User\Infrastructure\Service\ThrottlePasswordResetRequestService;
use App\User\Domain\Factory\UserFromModelEntityFactory;

class ThrottlePasswordResetRequestServiceTest extends TestCase
{
    private $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->user = $this->createUser();
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
            PasswordResetRequest::truncate();
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    private function createUser(): User
    {
        return User::create([
            'first_name' => 'Sergio',
            'last_name' => 'Ramos',
            'email' => "real-madrid".rand(). "@test.com",
            'password' => 'el-capitán-1234',
            'bio' => 'Real Madrid player',
            'location' => 'Madrid',
            'skills' => ['Football', 'Leadership'],
            'profile_image' => 'https://example.com/sergio.jpg'
        ]);
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
            ->andReturn(new UserId($this->user->id));

        $entity
            ->shouldReceive('getFirstName')
            ->andReturn($this->createUser()['first_name']);

        $entity
            ->shouldReceive('getLastName')
            ->andReturn($this->createUser()['last_name']);

        $hashed = bcrypt($this->createUser()['password']);
        $entity
            ->shouldReceive('getPassword')
            ->andReturn(Password::fromHashed($hashed));

        $entity
            ->shouldReceive('getEmail')
            ->andReturn(new Email($this->createUser()['email']));

        $entity
            ->shouldReceive('getBio')
            ->andReturn($this->createUser()['bio']);

        return $entity;
    }


    public function test_throttle_password_request_correct_request(): void
    {
        for ($i = 0; $i < 3; $i++) {
            PasswordResetRequest::create([
                'user_id' => $this->user->id,
                'token' => 'token-' . $i,
                'requested_at' => now()->subMinutes(10),
                'expired_at' => now()->addHour(),
                'created_at' => now()->subMinutes(10),
                'updated_at' => now()->subMinutes(10),
            ]);
        }


        $service = new ThrottlePasswordResetRequestService(new User());

        $this->expectNotToPerformAssertions();
        $service->checkThrottling($this->mockEntity());
    }

    public function test_throttle_password_request_incorrect_request(): void
    {
        for ($i = 0; $i < 6; $i++) {
            PasswordResetRequest::create([
                'user_id' => $this->user->id,
                'token' => 'token-' . $i,
                'requested_at' => now()->subMinutes(10),
                'expired_at' => now()->addHour(),
                'created_at' => now()->subMinutes(10),
                'updated_at' => now()->subMinutes(10),
            ]);
        }

        $this->expectException(TooManyRequestsHttpException::class);
        $service = new ThrottlePasswordResetRequestService(new User());

        $service->checkThrottling($this->mockEntity());
    }

    public function test_throttle_password_request_wrong_user(): void
    {
        for ($i = 0; $i < 3; $i++) {
            PasswordResetRequest::create([
                'user_id' => $this->user->id,
                'token' => 'token-' . $i,
                'requested_at' => now()->subMinutes(10),
                'expired_at' => now()->addHour(),
                'created_at' => now()->subMinutes(10),
                'updated_at' => now()->subMinutes(10),
            ]);
        }

        $wrongUser = $this->createUser();
        $wrongUserEntity = UserFromModelEntityFactory::buildFromModel($wrongUser);

        $service = new ThrottlePasswordResetRequestService(new User());

        $this->expectNotToPerformAssertions();

        $service->checkThrottling($wrongUserEntity);
    }
}