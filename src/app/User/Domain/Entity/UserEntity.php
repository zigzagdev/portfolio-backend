<?php

namespace App\User\Domain\Entity;

use App\Common\Domain\Userid;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;

class UserEntity
{
    private readonly ?UserId $id;
    private readonly string $firstName;
    private readonly string $lastName;
    private readonly Email $email;
    private readonly Password $password;
    private readonly ?string $bio;
    private readonly ?string $location;
    private readonly array $skills;
    private readonly ?string $profileImage;

    public function __construct(
        ?UserId $id,
        string $firstName,
        string $lastName,
        Email $email,
        Password $password,
        ?string $bio = null,
        ?string $location = null,
        array $skills = [],
        ?string $profileImage = null
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->bio = $bio;
        $this->location = $location;
        $this->skills = $skills;
        $this->profileImage = $profileImage;
    }

    public function getUserId(): ?UserId
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getSkills(): array
    {
        return $this->skills;
    }

    public function getProfileImage(): ?string
    {
        return $this->profileImage;
    }
}