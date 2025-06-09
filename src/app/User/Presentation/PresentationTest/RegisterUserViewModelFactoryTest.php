<?php

namespace App\User\Presentation\PresentationTest;

use App\User\Application\Dto\RegisterUserDto;
use App\User\Application\Factory\RegisterUserDtoFactory;
use App\User\Domain\ValueObject\Email;
use App\User\Presentation\ViewModel\Factory\RegisterUserViewModelFactory;
use App\User\Presentation\ViewModel\RegisterUserViewModel;
use App\Common\Domain\ValueObject\UserId;
use Mockery;
use Tests\TestCase;

class RegisterUserViewModelFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockDto(): RegisterUserDto
    {
        $factory = Mockery::mock(
            'alias'. RegisterUserDtoFactory::class
        );

        $dto = Mockery::mock(RegisterUserDto::class);

        $factory
            ->shouldReceive('build')
            ->andReturn($dto);

        $dto
            ->shouldReceive('getId')
            ->andReturn(new UserId($this->arrayRequestData()['id']));

        $dto
            ->shouldReceive('getFirstName')
            ->andReturn($this->arrayRequestData()['first_name']);

        $dto
            ->shouldReceive('getLastName')
            ->andReturn($this->arrayRequestData()['last_name']);

        $dto
            ->shouldReceive('getEmail')
            ->andReturn(new Email($this->arrayRequestData()['email']));

        $dto
            ->shouldReceive('getBio')
            ->andReturn($this->arrayRequestData()['bio']);

        $dto
            ->shouldReceive('getLocation')
            ->andReturn($this->arrayRequestData()['location']);

        $dto
            ->shouldReceive('getSkills')
            ->andReturn($this->arrayRequestData()['skills']);

        $dto
            ->shouldReceive('getProfileImage')
            ->andReturn($this->arrayRequestData()['profile_image']);

        return $dto;
    }

    private function arrayRequestData(): array
    {
        return [
            'id' => 1,
            'first_name' => 'Cristiano',
            'last_name' => 'Ronaldo',
            'email' => 'manchester7@test.com',
            'bio' => 'I am a football player',
            'location' => 'Manchester',
            'skills' => ['Laravel', 'React'],
            'profile_image' => 'https://example.com/profile.jpg',
        ];
    }

    /**
     * @test
     * @testdox RegisterUserViewModelFactory_build_successfully
     */
    public function test1(): void
    {
        $dto = $this->mockDto();
        $result = RegisterUserViewModelFactory::build($dto);

        $this->assertInstanceOf(RegisterUserViewModel::class, $result);
    }

    /**
     * @test
     * @testdox RegisterUserViewModelFactory_build_successfully check value
     */
    public function test2(): void
    {
        $dto = $this->mockDto();
        $result = RegisterUserViewModelFactory::build($dto);

        $this->assertEquals($this->arrayRequestData()['id'], $result->toArray()['id']);
        $this->assertEquals($this->arrayRequestData()['first_name'], $result->toArray()['first_name']);
        $this->assertEquals($this->arrayRequestData()['last_name'], $result->toArray()['last_name']);
        $this->assertEquals($this->arrayRequestData()['email'], $result->toArray()['email']);
        $this->assertEquals($this->arrayRequestData()['bio'], $result->toArray()['bio']);
        $this->assertEquals($this->arrayRequestData()['location'], $result->toArray()['location']);
        $this->assertEquals($this->arrayRequestData()['skills'], json_decode($result->toArray()['skills'], true));
        $this->assertEquals($this->arrayRequestData()['profile_image'], $result->toArray()['profile_image']);
    }
}