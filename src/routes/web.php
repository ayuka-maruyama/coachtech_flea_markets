<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemDetailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// view作成用。あとで削除する
// Route::get('/', function () {
//     return view('layouts.app');
// });

// ホーム画面
Route::get('/', [ItemController::class, 'index'])->name('home');
Route::get('/item/{item_id}', [ItemDetailController::class, 'open'])->name('detail.open');
Route::post('/item/{item_id}', [ItemDetailController::class, 'open'])->name('detail.open');
Route::post('/item/{item_id}/comment', [ItemDetailController::class, 'comment'])->name('comment');


// 新規登録
Route::get('/register', [RegisterController::class, 'open'])->name('register.open');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/thanks', function () {
    return view('auth.thanks');
})->name('register.thanks');

// ログイン
Route::get('/login', [LoginController::class, 'open'])->name('login.open');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

// メール認証
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [VerifyEmailController::class, 'confirmation'])->name('verification.notice');
});
Route::middleware(['signed', 'throttle:6,1'])->group(function () {
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->name('verification.verify');
});
Route::middleware(['auth', 'throttle:6,1'])->group(function () {
    Route::post('/email/verification-notification', [VerifyEmailController::class, 'resend'])->name('verification.send');
});

// ログアウト
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 商品購入
Route::post('/purchase/{item_id}', [PurchaseController::class, 'open'])->name('purchase');

// お気に入り登録
Route::post('/favorite/toggle/{item_id}', [ItemDetailController::class, 'toggle'])->name('favorite.toggle');

// マイページ遷移
Route::middleware('auth', 'verified')->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'open'])->name('profile');
    Route::post('/mypage/profile', [ProfileController::class, 'store'])->name('profile.store');
});
