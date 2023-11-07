<?php

use Femst\Like\Http\Controllers\LikeController;
use Femst\Like\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

// Route::name('like.')->group(function () {
//     Route::get('/get', [LikeController::class, 'get'])->name('get');
//     Route::post('/create', [LikeController::class, 'store'])->name('store');
//     Route::delete('/delete', [LikeController::class, 'delete'])->name('delete');
// });

Route::name('like.')->group(function() {
    Route::post('/create', [LikeController::class, 'store'])->name('store');
});