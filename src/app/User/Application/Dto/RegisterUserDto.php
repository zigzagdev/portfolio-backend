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

    public function toArray(): array
    {
        return [
            'id' => $this->id->getUserId(),
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