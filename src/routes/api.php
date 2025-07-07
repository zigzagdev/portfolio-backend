<?php

use App\User\Presentation\Controller\UserController;
use App\Post\Presentation\Controller\PostController;
use Illuminate\Support\Facades\Route;


Route::post('logout', [UserController::class, 'logout'])->name('logout');

Route::prefix('users')->name('user.')->group(function () {
    Route::post('register', [UserController::class, 'createUser'])->name('register');
    Route::post('login', [UserController::class, 'login'])->name('login');
    Route::get('/show/{id}', [UserController::class, 'showUser'])->name('show');
    Route::put('{id}', [UserController::class, 'update'])->name('update');
    Route::post('/password-reset', [UserController::class, 'passwordResetRequest'])->name('passwordResetRequest');
    Route::post('/password-reset/confirm', [UserController::class, 'passwordReset'])->name('passwordReset');

    Route::prefix('{userId}/posts')->name('posts.')->group(function () {
        Route::post('/', [PostController::class, 'create'])->name('create');
        Route::get('/', [PostController::class, 'getAllPosts'])->name('getAllPosts');
        Route::get('public', [PostController::class, 'getOthersPosts'])->name('getOthersPosts');
        Route::get('{postId}', [PostController::class, 'getEachPost'])->name('getEachPost');
        Route::put('{userId}/posts/{postId}', [PostController::class, 'edit'])->name('posts.edit');
    });
});