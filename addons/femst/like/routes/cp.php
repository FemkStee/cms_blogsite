<?php

use Femst\Like\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;

Route::name('like.')->group(function() {
    Route::get('/like', [LikeController::class, 'index'])->name('index');
});