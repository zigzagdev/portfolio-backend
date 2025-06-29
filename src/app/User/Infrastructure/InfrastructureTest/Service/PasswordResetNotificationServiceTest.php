<?php

namespace App\User\Infrastructure\InfrastructureTest\Service;

use App\Common\Domain\ValueObject\UserId;
use App\Models\User;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Factory\UserEntityFactory;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Mockery;
use App\User\Infrastructure\Service\PasswordResetNotificationService;
use App\User\Infrastructure\Mail\PasswordResetNotification;

class PasswordResetNotificationServiceTest extends TestCase
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


    public function test_sent_reset_link(): void
    {
        Mail::fake();

        $user = $this->mockEntity();
        $token = 'test-reset-token-123456';

        $service = new PasswordResetNotificationService();
        $service->sendResetLink($user, $token);

        Mail::assertSent(PasswordResetNotification::class, function ($mail) use ($user, $token) {

            return $mail->hasTo($user->getEmail()->getValue()) &&
                $mail->token === $token &&
                $mail->envelope()->subject === 'Reset Your Password' &&
                $mail->content()->view === 'emails.password_reset' &&
                $mail->content()->with['resetUrl'] === url("/password-reset/{$token}");
        });
    }
}