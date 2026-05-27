<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Aed\IndexPageController;
use App\Http\Controllers\Aed\AedDetailPageController;
use App\Http\Controllers\Aed\DeleteAed\DeleteAedController;

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

    Route::get('aed/detail/{aedId}', AedDetailPageController::class)
        ->name('aed-detail')->where('aedId', '[0-9]++');
    
    Route::delete('aed/delete/{aedId}', DeleteAedController::class)
        ->name('aed-delete')->where('aedId', '[0-9]++');

});

require __DIR__.'/auth.php';
