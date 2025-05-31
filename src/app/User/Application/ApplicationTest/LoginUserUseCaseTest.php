<?php

namespace App\User\Application\ApplicationTest;

use App\User\Application\Dto\LoginUserDto;
use App\User\Application\UseCase\LoginUserUseCase;
use App\User\Domain\Entity\UserEntity;
use App\User\Domain\Service\AuthServiceInterface;
use App\User\Domain\Service\GenerateTokenInterface;
use App\User\Domain\ValueObject\AuthToken;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use App\User\Domain\RepositoryInterface\PasswordHasherInterface;
use Exception;
use PHPUnit\Framework\TestCase;
use Mockery;

class LoginUserUseCaseTest extends TestCase
{
    private AuthServiceInterface $authService;
    private GenerateTokenInterface $tokenService;
    private PasswordHasherInterface $hasher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authService = Mockery::mock(AuthServiceInterface::class);
        $this->tokenService = Mockery::mock(GenerateTokenInterface::class);
        $this->hasher = Mockery::mock(PasswordHasherInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function arrayData(): array
    {
        return [
            'email' => 'manchester-untited@test.com',
            'password' => 'valid-password',
        ];
    }

    /**
     * @test
     * @testdox LoginUserUseCaseTest_successfully
     */
    public function test1(): void
    {
        $objectEmail = new Email($this->arrayData()['email']);
        $obujcectPassword = Password::fromHashed($this->arrayData()['password'], $this->hasher);

        $user = Mockery::mock(UserEntity::class);
        $token = new AuthToken('mocked-jwt-token');

        $this->hasher
            ->shouldReceive('hash')
            ->with($this->arrayData()['password'])
            ->andReturn($this->arrayData()['password']);

        $this->authService
            ->shouldReceive('attemptLogin')
            ->withArgs(function ($emailArg, $passwordArg) use ($objectEmail, $obujcectPassword) {
                return $emailArg instanceof Email && $emailArg->getValue() === $objectEmail->getValue()
                    && $passwordArg instanceof Password;
            })
            ->andReturn($user);


        $this->tokenService
            ->shouldReceive('generate')
            ->with($user)
            ->andReturn($token);

        $useCase = new LoginUserUseCase(
            $this->authService,
            $this->tokenService,
            $this->hasher
        );

        $dto = $useCase->handle(
            $this->arrayData()['email'],
            $this->arrayData()['password']
        );

        $this->assertInstanceOf(LoginUserDto::class, $dto);
        $this->assertEquals($token->getValue(), $dto->getToken()->getValue());
    }

    /**
     * @test
     * @testdox LoginUserUseCaseTest_fail_invalid_credentials
     */
    public function test2(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid credentials');

        $email = 'fail@example.com';
        $plainPassword = 'wrong-password';
        $hashedPassword = 'fake-hash';

        $emailVO = new Email($email);

        $this->hasher
            ->shouldReceive('hash')
            ->with($plainPassword)
            ->andReturn($hashedPassword);

        $this->authService
            ->shouldReceive('attemptLogin')
            ->andReturn(null);

        $useCase = new LoginUserUseCase(
            $this->authService,
            $this->tokenService,
            $this->hasher
        );

        $useCase->handle($email, $plainPassword);
    }
}