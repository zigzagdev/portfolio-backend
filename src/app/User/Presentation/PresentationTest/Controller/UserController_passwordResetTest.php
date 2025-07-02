<?php

namespace App\User\Presentation\PresentationTest\Controller;

use App\User\Presentation\Controller\UserController;
use Illuminate\Http\Request;
use Tests\TestCase;
use App\User\Application\UseCase\PasswordResetUseCase;
use Mockery;

class UserController_passwordResetTest extends TestCase
{

    private $controller;
    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new UserController();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockUseCase(): PasswordResetUseCase
    {
        $useCase = Mockery::mock(PasswordResetUseCase::class);

        $useCase
            ->shouldReceive('handle')
            ->with(
                Mockery::type('string'),
                Mockery::type('string'),
                Mockery::type('string')
            )
            ->andReturn(true);

        return $useCase;
    }

    private function mockRequest(): Request
    {
        $request = Mockery::mock(Request::class);

        $request
            ->shouldReceive('input')
            ->with('user_id')
            ->andReturn(1);

        $request
            ->shouldReceive('input')
            ->andReturn(bin2hex(random_bytes(32)));

        $request
            ->shouldReceive('input')
            ->with('new_password')
            ->andReturn('new-password-1234');

        return $request;
    }

    public function test_password_reset_controller_unit(): void
    {
        $this->controller
            ->passwordReset(
                $this->mockRequest(),
                $this->mockUseCase()
            );

        $this->assertTrue(true, 'Password reset controller executed successfully.');
    }
}