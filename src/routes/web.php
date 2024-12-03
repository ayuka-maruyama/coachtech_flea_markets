<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/', function () {
    return view('layouts/app');
});

Route::get('/register', function () {
    return view('auth/register');
});

// メール確認通知の表示ルート
Route::get('/email/verify', function () {
    return view('auth.verify-email'); // カスタムビュー
})->middleware(['auth'])->name('verification.notice');

// メール確認処理ルート
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // メール認証を完了する
    return redirect('/'); // 認証後のリダイレクト先
})->middleware(['auth', 'signed'])->name('verification.verify');

// 再送信ルート
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
