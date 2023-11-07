<?php

use Femst\Like\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::name('like.')->group(function() {
    Route::get('/like', [SettingsController::class, 'index'])->name('index');
});