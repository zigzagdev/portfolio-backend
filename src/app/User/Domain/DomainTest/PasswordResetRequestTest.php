<?php

namespace App\User\Domain\DomainTest;

use DateTimeImmutable;
use Tests\TestCase;
use App\User\Domain\Factory\PasswordRequestEntityFactory;
use App\User\Domain\Entity\PasswordRequestEntity;
use Illuminate\Support\Str;

class PasswordResetRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    private function arrayData(): array
    {
        return [
            'id' => 1,
            'user_id' => 1,
            'token' => Str::random(33),
            'requested_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
            'expired_at' => (new DateTimeImmutable('+1 hour'))->format('Y-m-d H:i:s'),
        ];
    }

    public function test_check_entity_type(): void
    {
        $result = PasswordRequestEntityFactory::build($this->arrayData());

        $this->assertInstanceOf(PasswordRequestEntity::class, $result);
    }

    public function test_check_entity_properties(): void
    {
        $result = PasswordRequestEntityFactory::build($this->arrayData());

        $this->assertEquals($this->arrayData()['id'], $result->getId());
        $this->assertEquals($this->arrayData()['user_id'], $result->getUserId()->getValue());
        $this->assertSame(33, strlen($result->getToken()->getValue()));
        $this->assertEquals($this->arrayData()['requested_at'], $result->getRequestedAt()->format('Y-m-d H:i:s'));
        $this->assertEquals($this->arrayData()['expired_at'], $result->getExpiredAt()->getValue()->format('Y-m-d H:i:s'));
    }
}
