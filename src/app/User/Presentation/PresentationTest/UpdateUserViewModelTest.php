<?php

namespace App\User\Presentation\PresentationTest;

use App\User\Application\Dto\UpdateUserDto;
use App\User\Domain\ValueObject\Email;
use App\User\Presentation\ViewModel\UpdateUserViewModel;
use Common\Domain\ValueObjet\UserId;
use Mockery;
use Tests\TestCase;

class UpdateUserViewModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function mockDto(): UpdateUserDto
    {
        $dto = Mockery::mock(UpdateUserDto::class);

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
            ->andReturn($this->arrayRequestData()['skills']);

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
            'skills' => ['football', 'coaching'],
            'profile_image' => 'https://example.com/image.jpg',
        ];
    }

    public function test1(): void
    {
        $result = UpdateUserViewModel::buildFromDto($this->mockDto());

        $this->assertInstanceOf(UpdateUserViewModel::class, $result);
    }

    /**
     * @test
     * @testdox ShowUserViewModelTest_build_successfully check value
     */
    public function test2(): void
    {
        $result = UpdateUserViewModel::buildFromDto($this->mockDto())->toArray();

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