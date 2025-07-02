<?php

namespace App\User\Application\ApplicationTest;

use Tests\TestCase;
use App\User\Application\UseCase\PasswordResetUseCase;
use App\User\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Common\Domain\ValueObject\UserId;
use App\User\Domain\ValueObject\PasswordResetToken;
use App\User\Domain\Service\PasswordResetTokenValidatorInterface;
use Mockery;

class PasswordResetUseCaseTest extends TestCase
{
    private $userId;
    private string $token;

    private string $newPassword;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userId = 1;
        $this->token = bin2hex(random_bytes(32));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockRepository(): UserRepositoryInterface
    {
        $repository = Mockery::mock(UserRepositoryInterface::class);

        $repository
            ->shouldReceive('resetPassword')
            ->with(
                Mockery::type(UserId::class),
                Mockery::type(PasswordResetToken::class),
                Mockery::type('string')
            )
            ->andReturn(true);

        return $repository;
    }

    private function mockTokenValidator(): PasswordResetTokenValidatorInterface
    {
        $tokenValidator = Mockery::mock(PasswordResetTokenValidatorInterface::class);

        $tokenValidator
            ->shouldReceive('validate')
            ->with(
                Mockery::type('string'),
                Mockery::type('string')
            )
            ->andReturn(true);

        return $tokenValidator;
    }

    public function test_use_case_ok(): void
    {
        $useCase = new PasswordResetUseCase(
            $this->mockRepository(),
            $this->mockTokenValidator()
        );

        $useCase->handle(
            $this->userId,
            $this->token,
            'new-password-1234'
        );

        $this->assertTrue(true, 'Password reset use case executed successfully');
    }
}