<?php

namespace  App\User\Application\ApplicationTest;

use Illuminate\Http\Request;
use Tests\TestCase;
use Mockery;
use App\User\Application\Factory\RegisterUserCommandFactory;
use App\User\Application\UseCommand\RegisterUserCommand;

class RegisterUserCommandFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     * @testdox RegisterUserCommandFactoryTest_build_successfully check type
     */
    public function test1(): void
    {
        $result = RegisterUserCommandFactory::build($this->mockRequest());

        $this->assertInstanceOf(RegisterUserCommand::class, $result);
    }

    /**
     * @test
     * @testdox RegisterUserCommandFactoryTest_build_successfully check value
     */
    public function test2(): void
    {
        $result = RegisterUserCommandFactory::build($this->mockRequest());
        foreach ($this->arrayRequestData() as $key => $expectedValue) {
            $camelKey = lcfirst(str_replace('_', '', ucwords($key, '_')));
            $getter = 'get' . ucfirst($camelKey);

            $this->assertEquals($expectedValue, $result->$getter());
        }
    }

    private function mockRequest(): Request
    {
        $mockRequest = Mockery::mock(Request::class);

        $mockRequest
            ->shouldReceive('toArray')
            ->andReturn($this->arrayRequestData());

        return $mockRequest;
    }

    private function arrayRequestData(): array
    {
        return [
            'first_name' => 'Cristiano',
            'last_name' => 'Ronaldo',
            'bio' => 'I am a football player',
            'email' => 'manchester-united7@test.com',
            'password' => 'test1234',
            'location' => 'Manchester',
            'skills' => ['Laravel', 'React'],
            'profile_image' => 'https://example.com/profile.jpg',
        ];
    }
}