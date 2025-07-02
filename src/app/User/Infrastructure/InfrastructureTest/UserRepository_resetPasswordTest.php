<?php

namespace App\User\Infrastructure\InfrastructureTest;

use App\Common\Domain\ValueObject\UserId;
use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\User\Infrastructure\Repository\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\User\Infrastructure\Service\BcryptPasswordHasher;
use App\User\Domain\ValueObject\PasswordResetToken;
use Exception;

class UserRepository_resetPasswordTest extends TestCase
{
    private $user;
    private $repository;
    protected function setUp(): void
    {
        parent::setUp();
        $this->refresh();
        $this->user = $this->createUser();
        $this->repository = new UserRepository(
            new BcryptPasswordHasher(),
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

    private function createUser(): User
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
        ]);
    }

    public function test_reset_password_ok(): void
    {
        $objectId = new UserId($this->user->id);
        $token = new PasswordResetToken(bin2hex(random_bytes(32)));
        $newPassword = 'test1234test1234test1234test1234';

        $this->repository->savePasswordResetToken($objectId, $token);

        $this->repository->resetPassword($objectId, $token, $newPassword);

        $updatedUser = $this->user->find($this->user->id);

        $this->assertTrue(
            Hash::check($newPassword, $updatedUser->password)
        );
    }

    public function test_reset_password_invalid_userId(): void
    {
        $this->expectException(Exception::class);

        $objectId = new UserId(100);
        $token = new PasswordResetToken(bin2hex(random_bytes(32)));
        $newPassword = 'test1234test1234test1234test1234';

        $this->repository->savePasswordResetToken($objectId, $token);

        $this->repository->resetPassword($objectId, $token, $newPassword);

        $updatedUser = $this->user->find($this->user->id);

        $this->assertTrue(
            Hash::check($newPassword, $updatedUser->password)
        );
    }
}