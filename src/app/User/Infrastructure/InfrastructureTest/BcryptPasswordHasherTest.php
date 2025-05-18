<?php

namespace Tests\Unit\Infrastructure\Auth;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\User\Infrastructure\BcryptPasswordHasher;

class BcryptPasswordHasherTest extends TestCase
{
    /**
     * @test
     * @testdox hasher test successfully
     */
    public function it_hashes_plain_text_password_correctly(): void
    {
        $hasher = new BcryptPasswordHasher();
        $plain = 'secure-password-123';

        $hashed = $hasher->hash($plain);

        $this->assertNotEquals($plain, $hashed);
        $this->assertTrue(Hash::check($plain, $hashed));
    }
}
