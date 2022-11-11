<?php

use App\Http\Controllers\Auth;
use App\Http\Controllers\Current;
use App\Http\Controllers\Posts;
use App\Http\Controllers\Tags;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('auth.')->group(function () {
    Route::post('/auth/login', Auth\LoginController::class)->name('login');
    Route::post('/auth/register', Auth\RegisterController::class)->name('register');
    Route::post('/auth/logout', Auth\LogoutController::class)->name('logout');
});

Route::middleware('auth:sanctum')->name('api.')->prefix('v1')->group(function () {

    Route::prefix('current')->name('current.')->group(function () {
        Route::get('/profile', Current\ProfileController::class)->name('profile');
        Route::put('/profile', Current\UpdateController::class)->name('profile.update');
        Route::patch('/email', Current\EmailController::class)->name('email');
        Route::patch('/password', Current\PasswordController::class)->name('password');
    });

    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/search', Posts\SearchController::class)->name('search');
        Route::get('/', Posts\IndexController::class)->name('index');
        Route::post('/', Posts\StoreController::class)->name('store');
        Route::get('/{post}', Posts\ShowController::class)->name('show');
        Route::put('/{post}', Posts\UpdateController::class)->name('update');
        Route::patch('/{post}/move', Posts\PublishedAtController::class)->name('move');
        Route::delete('/{post}', Posts\DeleteController::class)->name('delete');

        Route::post('/{post}/tags/{tag}', Posts\Tags\AttachController::class)->name('tags.attach');
        Route::delete('/{post}/tags/{tag}', Posts\Tags\DetachController::class)->name('tags.detach');
    });

    Route::prefix('tag_groups')->name('tag_groups.')->group(function () {
        Route::get('/', Tags\Groups\IndexController::class)->name('index');
        Route::post('/', Tags\Groups\StoreController::class)->name('store');
        Route::get('/{group}', Tags\Groups\ShowController::class)->name('show');
        Route::put('/{group}', Tags\Groups\UpdateController::class)->name('update');
        Route::delete('/{group}', Tags\Groups\DeleteController::class)->name('delete');
    });

    Route::prefix('tags')->name('tags.')->group(function () {
        Route::get('/', Tags\IndexController::class)->name('index');
        Route::post('/', Tags\StoreController::class)->name('store');
        Route::put('/{tag}', Tags\UpdateController::class)->name('update');
        Route::delete('/{tag}', Tags\DeleteController::class)->name('delete');
    });
});
