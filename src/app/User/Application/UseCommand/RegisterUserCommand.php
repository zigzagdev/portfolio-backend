<?php

namespace App\User\Application\UseCommand;

class RegisterUserCommand
{
    private string $firstname;
    private string $lastname;
    private string $email;
    private string $password;
    private ?string $bio;
    private ?string $location;
    private array $skills;
    private ?string $profileImage;

    public function __construct(
        string $firstname,
        string $lastname,
        string $email,
        string $password,
        ?string $bio = null,
        ?string $location = null,
        array $skills = [],
        ?string $profileImage = null
    ) {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password = $password;
        $this->bio = $bio;
        $this->location = $location;
        $this->skills = $skills;
        $this->profileImage = $profileImage;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
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

    public function toArray(): array
    {
        return [
            'first_name' => $this->firstname,
            'last_name' => $this->lastname,
            'email' => $this->email,
            'password' => $this->password,
            'bio' => $this->bio,
            'location' => $this->location,
            'skills' => $this->skills,
            'profile_image' => $this->profileImage,
        ];
    }
}