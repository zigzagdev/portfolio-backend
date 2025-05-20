<?php

namespace App\User\Presentation\PresentationTest\Controller;

use App\Common\Domain\UserId;
use App\User\Application\UseCase\RegisterUserUsecase;
use App\User\Application\UseCommand\RegisterUserCommand;
use App\User\Presentation\Controller\UserController;
use App\User\Application\Dto\RegisterUserDto;
use Illuminate\Http\Request;
use App\User\Domain\ValueObject\Email;
use Tests\TestCase;
use Mockery;

class UserController_registerTest extends TestCase
{
    private UserController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new UserController();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     * @testdox UserController_registerTest_successfully
     */
    public function test1(): void
    {
        $request = $this->mockRequest();
        $useCase = $this->mockUseCase();
        $this->mockCommand();

        $response = $this->controller->createUser($request, $useCase);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('success', json_decode($response->getContent())->status);
    }

    private function mockRequest(): Request
    {
        $request = Mockery::mock(Request::class);

        $request
            ->shouldReceive('toArray')
            ->andReturn($this->arrayTestData());

        return $request;
    }

    private function mockCommand(): RegisterUserCommand
    {
        $factory = Mockery::mock(
            'alias'. RegisterUserCommand::class
        );

        $command = Mockery::mock(RegisterUserCommand::class);

        $factory
            ->shouldReceive('build')
            ->andReturn($command);

        $command
            ->shouldReceive('getFirstName')
            ->andReturn($this->arrayTestData()['first_name']);

        $command
            ->shouldReceive('getLastName')
            ->andReturn($this->arrayTestData()['last_name']);

        $command
            ->shouldReceive('getEmail')
            ->andReturn($this->arrayTestData()['email']);

        $command
            ->shouldReceive('getPassword')
            ->andReturn($this->arrayTestData()['password']);

        $command
            ->shouldReceive('getBio')
            ->andReturn($this->arrayTestData()['bio']);

        $command
            ->shouldReceive('getLocation')
            ->andReturn($this->arrayTestData()['location']);

        $command
            ->shouldReceive('getSkills')
            ->andReturn($this->arrayTestData()['skills']);

        $command
            ->shouldReceive('getProfileImage')
            ->andReturn($this->arrayTestData()['profile_image']);

        return $command;
    }

    private function mockUseCase(): RegisterUserUsecase
    {
        $useCase = Mockery::mock(RegisterUserUsecase::class);

        $useCase
            ->shouldReceive('handle')
            ->andReturn($this->mockDto());

        return $useCase;
    }

    private function mockDto(): RegisterUserDto
    {
        $factory = Mockery::mock(
            'alias'. RegisterUserDto::class
        );

        $dto = Mockery::mock(RegisterUserDto::class);

        $factory
            ->shouldReceive('build')
            ->andReturn($dto);

        $dto
            ->shouldReceive('getId')
            ->andReturn(new UserId(1));

        $dto
            ->shouldReceive('getFirstName')
            ->andReturn($this->arrayTestData()['first_name']);

        $dto
            ->shouldReceive('getLastName')
            ->andReturn($this->arrayTestData()['last_name']);

        $dto
            ->shouldReceive('getEmail')
            ->andReturn(new Email($this->arrayTestData()['email']));

        $dto
            ->shouldReceive('getBio')
            ->andReturn($this->arrayTestData()['bio']);

        $dto
            ->shouldReceive('getLocation')
            ->andReturn($this->arrayTestData()['location']);

        $dto
            ->shouldReceive('getSkills')
            ->andReturn($this->arrayTestData()['skills']);

        $dto
            ->shouldReceive('getProfileImage')
            ->andReturn($this->arrayTestData()['profile_image']);

        return $dto;
    }

    private function arrayTestData(): array
    {
        return [
            'first_name' => 'Cristiano',
            'last_name' => 'Ronaldo',
            'email' => 'manchester7@test.com',
            'password' => 'test1234',
            'bio' => 'I am a football player',
            'location' => 'Manchester',
            'skills' => ['Laravel', 'React'],
            'profile_image' => 'https://example.com/profile.jpg',
        ];
    }
}