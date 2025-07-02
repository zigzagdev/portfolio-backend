<?php

namespace App\User\Infrastructure\InfrastructureTest;

use App\Models\User;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use App\User\Infrastructure\Repository\UserRepository;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use Carbon\Carbon;
use Mockery;
use App\User\Domain\ValueObject\PasswordResetToken;
use App\Common\Domain\ValueObject\UserId;
use App\Models\PasswordResetRequest;

class UserRepository_savePasswordResetTokenTest extends TestCase
{

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->user = new User();
        $this->userRepository = new UserRepository(
            Mockery::mock(PasswordHasherInterface::class),
            $this->user
        );
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

    private function mockPasswordToken(): PasswordResetToken
    {
        return new PasswordResetToken(
            'test-token-' . bin2hex(random_bytes(16))
        );
    }

    public function test_save_password_reset_token_check_type(): void
    {
        $userId = $this->user
            ->create([
                'first_name' => 'Toni',
                'last_name' => 'Kroos',
                'email' => 'real-madrid6@test.com',
                'password' => 'el-capitán-1234',
                'bio' => 'Real Madrid player',
                'location' => 'Madrid',
                'skills' => ['Football', 'Leadership'],
                'profile_image' => 'https://example.com/sergio.jpg'
            ])
            ->id;

        $resetRequest = PasswordResetRequest::create([
            'user_id' => $userId,
            'token' => 'initial-token',
            'requested_at' => Carbon::now()->subHours(2),
            'expired_at' => Carbon::now()->subHour(),
            'created_at' => Carbon::now()->subHours(2),
            'updated_at' => Carbon::now()->subHours(2),
        ]);

        $token = $this->mockPasswordToken();

        $this->userRepository->savePasswordResetToken(
            new UserId($userId),
            $token
        );

        $this->assertDatabaseHas('password_reset_requests', [
            'user_id' => $userId,
            'token' => $token->getValue(),
            'requested_at' => Carbon::now()->toDateTimeString(),
            'expired_at' => Carbon::now()->addHour()->toDateTimeString(),
        ]);
    }
}