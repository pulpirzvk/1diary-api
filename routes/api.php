<?php

use App\Http\Controllers\Posts;
use App\Http\Controllers\Tags;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('api.')->prefix('v1')->group(function () {
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', Posts\IndexController::class)->name('index');
        Route::post('/', Posts\StoreController::class)->name('store');
        Route::get('/{post}', Posts\ShowController::class)->name('show');
        Route::put('/{post}', Posts\UpdateController::class)->name('update');
        Route::delete('/{post}', Posts\DeleteController::class)->name('delete');

        Route::post('/{post}/tags/{tag}', Posts\Tags\AttachController::class)->name('tags.attach');
        Route::delete('/{post}/tags/{tag}', Posts\Tags\DetachController::class)->name('tags.detach');
    });

    Route::prefix('tags')->name('tags.')->group(function () {
        Route::get('/', Tags\IndexController::class)->name('index');
        Route::post('/', Tags\StoreController::class)->name('store');
        Route::put('/{tag}', Tags\UpdateController::class)->name('update');
        Route::delete('/{tag}', Tags\DeleteController::class)->name('delete');
    });
});
