<?php

namespace App\User\Application\UseCommand;

use App\Common\Domain\UserId;
use Illuminate\Http\Request;
use InvalidArgumentException;



class UpdateUserCommand
{
    public function __construct(
        public readonly int $id,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly ?string $bio,
        public readonly ?string $location,
        public readonly array $skills,
        public readonly ?string $profileImage
    ) {
    }

    public function getId(): int
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

    public function getEmail(): string
    {
        return $this->email;
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
        return $this->profileImage ?? null;
    }

    public static function build(
        Request $request
    ): self
    {
        $requestArray = $request->toArray();
        self::validation($requestArray);

        return new self(
            $requestArray['id'],
            $requestArray['first_name'],
            $requestArray['last_name'],
            $requestArray['email'],
            $requestArray['bio'] ?? null,
            $requestArray['location'] ?? null,
            $requestArray['skills'] ?? [],
            $requestArray['profile_image'] ?? null
        );
    }

    private static function validation(
        array $request
    ): bool
    {
        foreach (self::PROPERTIES as $property) {
            if (!array_key_exists($property, $request)) {
                throw new InvalidArgumentException("Missing required property: {$property}");
            }
        }
        return true;
    }

    private const PROPERTIES = [
        'id',
        'first_name',
        'last_name',
        'email',
        'bio',
        'location',
        'skills',
        'profile_image'
    ];

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'bio' => $this->getBio(),
            'location' => $this->getLocation(),
            'skills' => $this->getSkills(),
            'profile_image' => $this->getProfileImage(),
        ];
    }
}