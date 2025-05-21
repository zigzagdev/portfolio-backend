<?php

namespace App\User\Presentation\ViewModel;

use App\User\Application\Dto\ShowUserDto;

class ShowUserViewModel
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

    public static function buildFromDto(ShowUserDto $dto): self
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
            'skills' => json_encode($this->skills),
            'profile_image' => $this->profileImage ?? ''
        ];
    }
}