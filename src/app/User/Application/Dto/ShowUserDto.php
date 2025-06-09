<?php

namespace App\User\Application\Dto;

use App\User\Domain\Entity\UserEntity;
use App\User\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\UserId;

class ShowUserDto
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
    ) {
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

    public function getBio(): ?string
    {
        return $this->bio ?? null;
    }

    public function getLocation(): ?string
    {
        return $this->location ?? null;
    }

    public function getSkills(): array
    {
        return $this->skills;
    }

    public function getProfileImage(): ?string
    {
        return $this->profileImage ?? null;
    }

    public static function build(
        UserEntity $entity
    ): self
    {
        return new self(
            id: new UserId($entity->getUserId()->getValue()),
            firstName: $entity->getFirstName(),
            lastName: $entity->getLastName(),
            email: $entity->getEmail(),
            bio: $entity->getBio(),
            location: $entity->getLocation(),
            skills: $entity->getSkills(),
            profileImage: $entity->getProfileImage()
        );
    }
}