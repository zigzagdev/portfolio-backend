<?php

namespace App\User\Application\Factory;

use App\Models\User;
use App\User\Domain\ValueObject\Email;
use App\Common\Domain\UserId;
use App\User\Application\Dto\RegisterUserDto;

class RegisterUserDtoFactory
{
    public static function build(User $user): RegisterUserDto
    {
        $resultArray = $user->toArray();

        return new RegisterUserDto(
            new UserId($resultArray['id']),
            $resultArray['first_name'],
            $resultArray['last_name'],
            new Email($resultArray['email']),
            $resultArray['bio'] ?? null,
            $resultArray['location'] ?? null,
            is_string($resultArray['skills'])
                ? json_decode($resultArray['skills'], true)
                : (is_array($resultArray['skills']) ? $resultArray['skills'] : []),
            $resultArray['profile_image'] ?? null,
        );
    }
}