<?php

namespace App\User\Application\Dto;

use App\Common\Domain\UserId;
use App\User\Domain\ValueObject\Email;

class RegisterUserDto
{
    public function __construct(
        public readonly UserId $id,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly Email $email,
        public readonly ?string $bio,
        public readonly ?string $location,
        public readonly array $skills,
        public readonly ?string $profileImage
    ){

    }

    public function getId(): UserId
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

    public function getBio(): string
    {
        return $this->bio ?? '';
    }

    public function getLocation(): string
    {
        return $this->location ?? '';
    }

    public function getSkills(): array
    {
        return $this->skills;
    }

    public function getProfileImage(): string
    {
        return $this->profileImage ?? '';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->getValue(),
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email->getValue(),
            'bio' => $this->bio,
            'location' => $this->location,
            'skills' => json_encode($this->skills),
            'profile_image' => $this->profileImage
        ];
    }
}