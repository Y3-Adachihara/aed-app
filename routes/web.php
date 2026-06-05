<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Aed\IndexPageController;
use App\Http\Controllers\Aed\AedDetailPageController;
use App\Http\Controllers\Aed\DeleteAed\DeleteAedController;
use App\Http\Controllers\Aed\EditAed\EditAedPageController;
use App\Http\Controllers\Aed\EditAed\EditAedController;

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
    
    // ユーザ情報参照画面表示
    Route::get('userinfo-page', function() {
        return '現在開発中。待ちやがれ。';
    })->name('userinfo-page');

    // AED詳細画面表示
    Route::get('aed/detail/{aedId}', AedDetailPageController::class)
        ->name('aed-detail')->where('aedId', '[0-9]++');
    
    // 削除処理コントローラ呼び出し
    Route::delete('aed/delete/{aedId}', DeleteAedController::class)
        ->name('aed-delete')->where('aedId', '[0-9]++');

    // 編集画面表示
    Route::get('aed/edit-page/{aedId}', EditAedPageController::class)
        ->name('aed-edit-page')->where('aedId', '[0-9]++');

    // 編集処理コントローラ呼び出し
    Route::put('aed/edit/{aedId}', EditAedController::class)
        ->name('aed-edit')->where('aedId', '[0-9]++');

});

require __DIR__.'/auth.php';
