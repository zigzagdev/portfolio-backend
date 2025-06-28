<?php

namespace App\User\Presentation\PresentationTest\Controller;

use App\User\Application\UseCase\RequestUserPasswordResetUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;
use App\User\Presentation\Controller\UserController;

class UserController_passwordResetRequestTest extends TestCase
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

    private function mockUseCase(): RequestUserPasswordResetUseCase
    {
        $useCase = Mockery::mock(RequestUserPasswordResetUseCase::class);

        $useCase
            ->shouldReceive('handle')
            ->with(Mockery::type('string'))
            ->andReturn(true);

        return $useCase;
    }

    private function arrayData(): array
    {
        return [
            'first_name' => 'Andres',
            'last_name' => 'Iniesta',
            'bio' => 'Soccer player',
            'email' => 'barcelona8@test.com',
            'location' => 'Spain',
            'skills' => json_encode(['dribbling', 'passing']),
            'profile_image' => 'https://example.com/profile.jpg',
        ];
    }

    private function mockRequest(): Request
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('input')
            ->with('email')
            ->andReturn($this->arrayData()['email']);

        return $request;
    }

    public function test_controller(): void
    {
        $result = $this->controller->passwordResetRequest(
            $this->mockRequest(),
            $this->mockUseCase()
        );

        $this->assertInstanceOf(JsonResponse::class, $result);
    }
}