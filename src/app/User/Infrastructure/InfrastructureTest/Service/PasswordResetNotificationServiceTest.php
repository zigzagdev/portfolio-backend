<?php

namespace App\User\Infrastructure\InfrastructureTest\Service;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\User\Infrastructure\Service\PasswordResetNotificationService;
use App\User\Infrastructure\Mail\PasswordResetNotification;

class PasswordResetNotificationServiceTest extends TestCase
{
    private $userId;
    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->userId = $this->createUser();
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

    private function createUser(): int
    {
        return User::create([
            'first_name' => 'Sergio',
            'last_name' => 'Ramos',
            'email' => 'real-madrid15@test.com',
            'password' => 'el-capitán-1234',
            'bio' => 'Real Madrid player',
            'location' => 'Madrid',
            'skills' => ['Football', 'Leadership'],
            'profile_image' => 'https://example.com/sergio.jpg'
        ])->id;
    }

    public function test_sent_reset_link(): void
    {
        Mail::fake();

        $user = User::find($this->userId);
        $token = 'test-reset-token-123456';

        $service = new PasswordResetNotificationService();
        $service->sendResetLink($user, $token);

        Mail::assertSent(PasswordResetNotification::class, function ($mail) use ($user, $token) {

            return $mail->hasTo($user->email) &&
                $mail->token === $token &&
                $mail->envelope()->subject === 'Reset Your Password' &&
                $mail->content()->view === 'emails.password_reset' &&
                $mail->content()->with['resetUrl'] === url("/password-reset/{$token}");
        });
    }
}