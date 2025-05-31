<?php

namespace App\User\Presentation\PresentationTest\Controller;

use App\User\Domain\ValueObject\AuthToken;
use Tests\TestCase;
use App\User\Presentation\Controller\UserController;
use Illuminate\Http\Request;
use App\User\Application\UseCase\LoginUserUseCase;
use App\User\Application\Dto\LoginUserDto;
use Mockery;

class UserController_loginTest extends TestCase
{
    const MOCK_TOKEN = 'test-token';
    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new UserController();
        $this->token = new AuthToken(self::MOCK_TOKEN);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockUseCase(): LoginUserUseCase
    {
        $useCase = Mockery::mock(LoginUserUseCase::class);

        $useCase
            ->shouldReceive('handle')
            ->with(
                $this->arrayRequestData()['email'],
                $this->arrayRequestData()['password']
            )
            ->andReturn($this->mockDto());


        return $useCase;
    }

    private function mockDto(): LoginUserDto
    {
        $dto = Mockery::mock(LoginUserDto::class);

        $dto
            ->shouldReceive('getEmail')
            ->andReturn($this->arrayRequestData()['email']);

        $dto
            ->shouldReceive('getPassword')
            ->andReturn($this->arrayRequestData()['password']);

        $dto
            ->shouldReceive('getToken')
            ->andReturn(new AuthToken(self::MOCK_TOKEN));

        $dto
            ->shouldReceive('toArray')
            ->andReturn($this->arrayRequestData() + ['token' => self::MOCK_TOKEN]);

        return $dto;
    }

    private function mockRequest(): Request
    {
        $request = Mockery::mock(Request::class);

        $request
            ->shouldReceive('input')
            ->with('email')
            ->andReturn($this->arrayRequestData()['email']);

        $request
            ->shouldReceive('input')
            ->with('password')
            ->with('password')
            ->andReturn($this->arrayRequestData()['password']);

        return $request;
    }

    private function arrayRequestData(): array
    {
        return [
            'email' => 'liverpool-8@test.com',
            'password' => 'password1234',
        ];
    }

    /**
     * @test
     * @testdox UserController_loginTest_successfully
     */
    public function test1(): void
    {
        $response = $this->controller->login(
            $this->mockRequest(),
            $this->mockUseCase()
        );

        $this->assertEquals(200, $response->getStatusCode());
    }
}