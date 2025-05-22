<?php

use App\User\Presentation\Controller\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->name('user.')->group(function () {
    Route::post('register', [UserController::class, 'createUser'])->name('register');
    Route::get('show/{id}', [UserController::class, 'showUser'])->name('show');
});