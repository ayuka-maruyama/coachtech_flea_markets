<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('layouts/app');
});

// 新規登録
Route::get('/register', [RegisterController::class, 'open'])->name('register.open');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/thanks', function () {
    return view('auth.thanks');
})->name('register.thanks');

// メール確認通知の表示ルート
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware(['auth', 'verified'])->name('verification.notice');

// メール確認処理ルート
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

// 再送信ルート
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
