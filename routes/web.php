<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Aed\IndexPageController;

Route::get('/', function () {
    // ↓でhomeにアクセスしようとすると、自動的にログイン画面にリダイレクトされる…はず。
    return redirect()->route('home');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('home', IndexPageController::class)
        ->name('home');
    
    Route::get('userinfo-page', function() {
        return '現在開発中。待ちやがれ。';
    })->name('userinfo-page');
    
});

require __DIR__.'/auth.php';
