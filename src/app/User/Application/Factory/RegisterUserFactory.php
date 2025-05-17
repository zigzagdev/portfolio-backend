<?php

namespace App\User\Application\Factory;

use App\User\Application\UseCommand\RegisterUserCommand;
use Illuminate\Http\Client\Request;

class RegisterUserFactory
{
    public static function build(Request $request): RegisterUserCommand
    {
        $requestArray = $request->toArray();

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
}