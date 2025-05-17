<?php

namespace App\User\Presentation\ViewModel;

use App\User\Application\Dto\RegisterUserDto;

class RegisterUserViewModel
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $bio;
    private string $location;
    private array $skills;
    private string $profileImage;

    public function __construct(RegisterUserDto $dto)
    {
        $this->id = $dto->getId()->getValue();
        $this->firstName = $dto->getFirstName();
        $this->lastName = $dto->getLastName();
        $this->email = $dto->getEmail()->getValue();
        $this->bio = $dto->getBio() ?? '';
        $this->location = $dto->getLocation() ?? '';
        $this->skills = $dto->getSkills();
        $this->profileImage = $dto->getProfileImage() ?? '';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'bio' => $this->bio,
            'location' => $this->location,
            'skills' => json_encode($this->skills),
            'profile_image' => $this->profileImage
        ];
    }
}