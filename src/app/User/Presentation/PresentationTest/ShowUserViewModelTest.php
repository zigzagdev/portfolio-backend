<?php

namespace App\User\Presentation\PresentationTest;

use App\User\Application\Dto\ShowUserDto;
use App\User\Presentation\ViewModel\ShowUserViewModel;
use Tests\TestCase;
use Mockery;
use App\User\Domain\ValueObject\Email;
use App\Common\Domain\UserId;

class ShowUserViewModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockDto(): ShowUserDto
    {
        $dto = Mockery::mock(ShowUserDto::class);

        $dto
            ->shouldReceive('getId')
            ->andReturn(new Userid($this->arrayRequestData()['id']));

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
            ->shouldReceive('getProfileImage')
            ->andReturn($this->arrayRequestData()['profile_image']);

        $dto
            ->shouldReceive('getLocation')
            ->andReturn($this->arrayRequestData()['location']);

        $dto
            ->shouldReceive('getSkills')
            ->andReturn(json_decode($this->arrayRequestData()['skills'], true));

        return $dto;
    }

    private function arrayRequestData(): array
    {
        return [
            'id' => 1,
            'first_name' => 'zinedine',
            'last_name' => 'zidane',
            'email' => 'france10@test.com',
            'bio' => 'I am a football player',
            'location' => 'France',
            'skills' => json_encode(['football', 'coaching']),
            'profile_image' => 'https://example.com/image.jpg',
        ];
    }

    /**
     * @test
     * @testdox ShowUserViewModelTest_build_successfully check type
     */
    public function test1(): void
    {
        $result = ShowUserViewModel::buildFromDto($this->mockDto());

        $this->assertInstanceOf(ShowUserViewModel::class, $result);
    }

    /**
     * @test
     * @testdox ShowUserViewModelTest_build_successfully check value
     */
    public function test2(): void
    {
        $result = ShowUserViewModel::buildFromDto($this->mockDto())->toArray();

        $expectedFullName = $this->arrayRequestData()['first_name'] . ' ' . $this->arrayRequestData()['last_name'];

        $this->assertEquals($this->arrayRequestData()['id'], $result['id']);
        $this->assertEquals($expectedFullName, $result['full_name']);
        $this->assertEquals($this->arrayRequestData()['email'], $result['email']);
        $this->assertEquals($this->arrayRequestData()['bio'], $result['bio']);
        $this->assertEquals($this->arrayRequestData()['location'], $result['location']);
        $this->assertEquals($this->arrayRequestData()['skills'], $result['skills']);
        $this->assertEquals($this->arrayRequestData()['profile_image'], $result['profile_image']);
    }
}