<?php

namespace App\User\Domain\Entity;

use App\Common\Domain\UserId;
use App\User\Domain\ValueObject\Email;
use App\User\Domain\ValueObject\Password;
use LogicException;

class UserEntity
{
    private readonly string $firstName;
    private readonly string $lastName;
    private readonly Email $email;
    private readonly ?Password $password;
    private readonly ?string $bio;
    private readonly ?string $location;
    private readonly array $skills;
    private readonly ?string $profileImage;
    private readonly ?UserId $id;

    public function __construct(
        string $firstName,
        string $lastName,
        Email $email,
        ?Password $password = null,
        ?string $bio = null,
        ?string $location = null,
        array $skills = [],
        ?string $profileImage = null,
        ?UserId $id = null
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->bio = $bio;
        $this->location = $location;
        $this->skills = $skills;
        $this->profileImage = $profileImage;
        $this->id = $id;
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

    public function getPassword(): ?Password
    {
        return $this->password;
    }

    public function getValidatedHashedPassword(): string
    {
        if ($this->password === null) {
            throw new LogicException('Password is null.');
        }

        return $this->password->getHashedPassword();
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
