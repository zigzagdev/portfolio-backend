<?php

namespace App\User\Presentation\PresentationTest\Controller;

use App\User\Application\Dto\ShowUserDto;
use App\User\Application\UseCase\ShowUserUseCase;
use App\User\Presentation\Controller\UserController;
use App\User\Presentation\ViewModel\ShowUserViewModel;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use Mockery;
use App\Common\Domain\UserId;
use App\User\Domain\ValueObject\Email;

class UserController_showTest extends TestCase
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

    private function mockUseCase(): ShowUserUseCase
    {
        $useCase = Mockery::mock(ShowUserUseCase::class);

        $useCase
            ->shouldReceive('handle')
            ->andReturn($this->mockDto());

        return $useCase;
    }

    private function mockDto(): ShowUserDto
    {
        $dto = Mockery::mock(ShowUserDto::class);

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

    private function mockViewModel(): ShowUserViewModel
    {
        $viewModel = Mockery::mock(ShowUserViewModel::class);

        $viewModel
            ->shouldReceive('toArray')
            ->andReturn($this->arrayTestData());

        return $viewModel;
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
     * @testdox UserController_show_successfully
     */
    public function test1(): void
    {
        $useCase = $this->mockUseCase();

        $result = $this->controller
            ->showUser(
                $this->arrayTestData()['id'],
                $useCase,
            );

        $this->assertInstanceOf(JsonResponse::class, $result);
    }

    /**
     * @test
     * @testdox UserController_show_successfully check value
     */
    public function test2(): void
    {
        $useCase = $this->mockUseCase();

        $result = $this->controller
            ->showUser(
                $this->arrayTestData()['id'],
                $useCase,
            );
        $fullName = $this->arrayTestData()['first_name'] . ' ' . $this->arrayTestData()['last_name'];

        $this->assertEquals($this->arrayTestData()['id'], $result->getOriginalContent()['data']['id']);
        $this->assertEquals($fullName, $result->getOriginalContent()['data']['full_name']);
        $this->assertEquals($this->arrayTestData()['bio'], $result->getOriginalContent()['data']['bio']);
        $this->assertEquals($this->arrayTestData()['location'], $result->getOriginalContent()['data']['location']);
        $this->assertEquals($this->arrayTestData()['email'], $result->getOriginalContent()['data']['email']);
        $this->assertEquals($this->arrayTestData()['skills'], json_decode($result->getOriginalContent()['data']['skills'], true));
        $this->assertEquals($this->arrayTestData()['profile_image'], $result->getOriginalContent()['data']['profile_image']);
    }
}