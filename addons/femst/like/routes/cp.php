<?php

use Femst\Like\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::name('like.')->group(function() {
    Route::get('/like', [SettingsController::class, 'index'])->name('index');
    Route::get('/like/{slug}', [SettingsController::class, 'allEntryIds'])->name('allEntryIds'); // slug is entry_id
    Route::get('/add', [SettingsController::class, 'add'])->name('add');
    Route::get('/like/edit/{slug}', [SettingsController::class, 'edit'])->name('edit'); // slug is id
    Route::post('/update', [SettingsController::class, 'update'])->name('update');
    Route::post('/add', [SettingsController::class, 'store'])->name('store');
    Route::get('/like/delete/{slug}', [SettingsController::class, 'delete'])->name('delete'); // slug is id
});