<?php

namespace App\User\Presentation\ViewModel;

use App\User\Application\Dto\UpdateUserDto;

class UpdateUserViewModel
{
    public function __construct(
        public readonly int $id,
        public readonly string $fullName,
        public readonly string $email,
        public readonly ?string $bio,
        public readonly ?string $location,
        public readonly array $skills,
        public readonly ?string $profileImage,
    ) {}

    public static function buildFromDto(UpdateUserDto $dto): self
    {
        return new self(
            id: $dto->getId()->getValue(),
            fullName: $dto->getFirstName() . ' ' . $dto->getLastName(),
            email: $dto->getEmail()->getValue(),
            bio: $dto->getBio(),
            location: $dto->getLocation(),
            skills: $dto->getSkills(),
            profileImage: $dto->getProfileImage(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->fullName,
            'email' => $this->email,
            'bio' => $this->bio ?? '',
            'location' => $this->location ?? '',
            'skills' => $this->skills,
            'profile_image' => $this->profileImage ?? ''
        ];
    }
}