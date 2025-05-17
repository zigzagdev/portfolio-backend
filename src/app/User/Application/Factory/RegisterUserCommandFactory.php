<?php

namespace App\User\Application\Factory;

use App\User\Application\UseCommand\RegisterUserCommand;
use Illuminate\Http\Request;
use InvalidArgumentException;

class RegisterUserCommandFactory
{
    public static function build(Request $request): RegisterUserCommand
    {
        $requestArray = $request->toArray();

        self::validate($requestArray);

        return new RegisterUserCommand(
            $requestArray['first_name'],
            $requestArray['last_name'],
            $requestArray['email'],
            $requestArray['password'],
            $requestArray['bio'] ?? null,
            $requestArray['location'] ?? null,
            is_string($requestArray['skills'])
                ? json_decode($requestArray['skills'], true)
                : (is_array($requestArray['skills']) ? $requestArray['skills'] : []),
            $requestArray['profile_image'] ?? null,
        );
    }

    private static $properties = [
        'first_name',
        'last_name',
        'email',
        'password',
        'bio',
        'location',
        'skills',
        'profile_image'
    ];

    private static function validate(array $request): void
    {
        foreach (self::$properties as $property) {
            if (!array_key_exists($property, $request)) {
                throw new InvalidArgumentException("Missing required property: {$property}");
            }
        }
    }
}