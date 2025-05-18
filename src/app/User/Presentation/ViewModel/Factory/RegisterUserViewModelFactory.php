<?php

namespace App\User\Presentation\ViewModel\Factory;

use App\User\Application\Dto\RegisterUserDto;
use App\User\Presentation\ViewModel\RegisterUserViewModel;

class RegisterUserViewModelFactory
{
    public static function build(
        RegisterUserDto $dto
    ): RegisterUserViewModel
    {
        return new RegisterUserViewModel($dto);
    }
}