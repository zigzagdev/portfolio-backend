<?php

namespace App\User\Presentation\PresentationTest\Controller;

use App\User\Application\Dto\UpdateUserDto;
use App\User\Application\UseCase\UpdateUseCase;
use App\User\Application\UseCommand\UpdateUserCommand;
use App\User\Domain\ValueObject\Email;
use App\User\Presentation\Controller\UserController;
use Common\Domain\ValueObjet\UserId;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class UserController_updateTest extends TestCase
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

    private function mockUseCase(): UpdateUseCase
    {
        $useCase = Mockery::mock(UpdateUseCase::class);

        $useCase
            ->shouldReceive('handle')
            ->with($this->mockCommand())
            ->andReturn($this->mockDto());

        return $useCase;
    }

    private function mockCommand(): UpdateUserCommand
    {
        $command = Mockery::mock(UpdateUserCommand::class);

        $command
            ->shouldReceive('getId')
            ->andReturn($this->arrayTestData()['id']);

        $command
            ->shouldReceive('getFirstName')
            ->andReturn($this->arrayTestData()['first_name']);

        $command
            ->shouldReceive('getLastName')
            ->andReturn($this->arrayTestData()['last_name']);

        $command
            ->shouldReceive('getEmail')
            ->andReturn(new Email($this->arrayTestData()['email']));

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

    private function mockDto(): UpdateUserDto
    {
        $dto = Mockery::mock(UpdateUserDto::class);

        $dto
            ->shouldReceive('getId')
            ->andReturn(new UserId($this->arrayTestData()['id']));

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

    private function mockRequest(): Request
    {
        $request = Mockery::mock(Request::class);

        $request
            ->shouldReceive('toArray')
            ->andReturn($this->arrayTestData());

        return $request;
    }

    private function arrayTestData(): array
    {
        return [
            'id' => 1,
            'first_name' => 'Frank',
            'last_name' => 'Lampard',
            'bio' => 'A former footballer and current manager',
            'location' => 'London',
            'email' => 'chelsea8@test.com',
            'skills' => ['coaching', 'leadership'],
            'profile_image' => 'https://example.com/profile.jpg'
        ];
    }

    /**
     * @test
     * @testdox UserController_update_successfully
     */
    public function test1(): void
    {
        $useCase = $this->mockUseCase();

        $result = $this->controller
            ->update(
                $this->arrayTestData()['id'],
                $this->mockRequest(),
                $useCase,
            );

        $this->assertInstanceOf(JsonResponse::class, $result);
    }
}
