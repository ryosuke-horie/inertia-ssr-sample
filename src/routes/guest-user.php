<?php

use App\Http\Controllers\User\Auth\AuthenticatedSessionController;
use App\Http\Controllers\GuestUser\MypageController;
use App\Http\Controllers\GuestUser\BusinessOperatorController;
use App\Http\Controllers\GuestUser\StaffController;
use App\Http\Controllers\GuestUser\PointChargeController;
use App\Http\Controllers\GuestUser\NotificationController;
use App\Http\Controllers\User\LoginMethodController;
use App\Http\Controllers\User\SignupController;
use Illuminate\Support\Facades\Route;

Route::prefix('guest')->name('guest.')->group(function () {
    // ログアウト
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // 事業者
    Route::prefix('business-operator')->name('business-operator.')->group(function () {
        // 一覧表示
        Route::get('/', [BusinessOperatorController::class, 'index'])->name('index');
        // 詳細表示
        Route::get('/{businessId}', [BusinessOperatorController::class, 'show'])->name('show');
        // 口コミ登録
        Route::get('/{businessId}/review', [BusinessOperatorController::class, 'createReview'])->name('create-review');
        Route::post('/{businessId}/review', [BusinessOperatorController::class, 'storeReview'])->name('store-review');

        // スタッフ一覧
        Route::get('/{businessId}/staff', [StaffController::class, 'index'])->name('staff.index');
        // スタッフ詳細
        Route::get('/{businessId}/staff/{staffId}', [StaffController::class, 'show'])->name('staff.show');
        // スタッフへのチップクレジット決済
        Route::post('/{businessId}/staff/{staffId}/payment', [StaffController::class, 'payment'])->name('staff.userTip.payment');
        // スタッフへのチップ
        Route::post('/{businessId}/staff/{staffId}/payment/complete', [StaffController::class, 'paymentComplete'])->name('staff.userTip.payment.complete');
    });
});
