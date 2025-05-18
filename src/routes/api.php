<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\User\Presentation\Controller\UserController;

Route::post('user/register', [UserController::class, 'createUser'])->name('user.register');