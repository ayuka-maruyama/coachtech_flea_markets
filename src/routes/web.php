<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemDetailController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

// ホーム画面
Route::get('/', [ItemController::class, 'index'])->name('home');

// 新規登録
Route::get('/register', [RegisterController::class, 'open'])->name('register.open');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/thanks', function () {
    return view('auth.thanks');
})->name('register.thanks');

// ログイン
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form.show');
Route::post('/login', [LoginController::class, 'handleLogin'])->name('login.authenticate');

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

// 商品詳細画面
Route::get('/item/{item_id}', [ItemDetailController::class, 'open'])->name('detail.open');
Route::post('/item/{item_id}', [ItemDetailController::class, 'open'])->name('detail.open');
Route::post('/item/{item_id}/comment', [ItemDetailController::class, 'comment'])->name('comment');

// 商品購入
Route::get('/purchase/{item_id}', [PurchaseController::class, 'open'])->name('purchase');

// 配送先変更
Route::get('/purchase/address/{item_id}', [DestinationController::class, 'open'])->name('destination.change');
Route::post('/purchase/address/{item_id}', [DestinationController::class, 'update'])->name('destination.store');

// お気に入り登録
Route::post('/favorite/toggle/{item_id}', [ItemDetailController::class, 'toggle'])->name('favorite.toggle');

// マイページ遷移
Route::middleware('auth', 'verified')->group(function () {
    Route::post('/purchase/order/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/stripe/checkout/{order_id}', [StripeController::class, 'checkout'])->name('stripe.checkout');
    Route::get('/stripe/success/{order_id}', [StripeController::class, 'success'])->name('stripe.success');
    Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
    Route::get('/mypage', [MypageController::class, 'open'])->name('mypage.open');
    Route::get('/mypage/profile', [ProfileController::class, 'open'])->name('profile.open');
    Route::post('/mypage/profile', [ProfileController::class, 'storeOrUpdate'])->name('profile.storeOrUpdate');
    Route::patch('/mypage/profile', [ProfileController::class, 'storeOrUpdate'])->name('profile.storeOrUpdate');
    Route::get('/sell', [SellController::class, 'open'])->name('sell.open');
    Route::post('/sell', [SellController::class, 'store'])->name('sell.store');
});
