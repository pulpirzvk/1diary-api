<?php

use App\Http\Controllers\V1\Posts;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('api.v1.')->prefix('v1')->group(function () {
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', Posts\IndexController::class)->name('index');
        Route::post('/', Posts\StoreController::class)->name('store');
        Route::get('/{post}', Posts\ShowController::class)->name('show');
        Route::put('/{post}', Posts\UpdateController::class)->name('update');
        Route::delete('/{post}', Posts\DeleteController::class)->name('delete');
    });
});
