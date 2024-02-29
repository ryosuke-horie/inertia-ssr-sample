<?php

use App\Http\Controllers\AdminStaff\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AdminStaff\Auth\NewPasswordController;
use App\Http\Controllers\AdminStaff\Auth\PasswordResetLinkController;
use App\Http\Controllers\AdminStaff\Auth\RegisteredAdminStaffController;
use App\Http\Controllers\AdminStaff\MypageController;
use App\Http\Controllers\AdminStaff\TipController;
use App\Http\Controllers\Inquiry\InquiryController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin-staff')->name('admin-staff.')->group(function () {
    // ゲストルーティング
    Route::middleware('admin-staff.guest:admin-staff')->group(function () {
        Route::get('register', [RegisteredAdminStaffController::class, 'create'])->name('register');
        Route::post('register', [RegisteredAdminStaffController::class, 'store']);
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
    });

    // 認証済ルーティング
    Route::middleware('admin-staff:admin-staff')->group(function () {
        // マイページ
        Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
        Route::prefix('tips')->name('tips.')->group(function () {
            Route::get('/', [TipController::class, 'index'])->name('index');
            Route::prefix('{tipId}')->group(function () {
                Route::middleware('admin-staff.staff.tipId')->group(function () {
                    Route::get('/', [TipController::class, 'show'])->name('show');
                    Route::post('/', [TipController::class, 'create'])->name('create');
                    Route::middleware('admin-staff.staff.tipId.replyId')->group(function () {
                        Route::delete('/replies/{replyId}', [TipController::class, 'delete'])->name('delete');
                    });
                });
            });
        });

        // お問い合わせ
        Route::prefix('inquiry')->name('inquiry.')->group(function () {
            // 初期画面表示
            Route::get('/', [InquiryController::class, 'index'])->name('index');
            // 確認画面表示
            Route::post('/confirm', [InquiryController::class, 'confirm'])->name('confirm');
            // 送信完了処理
            Route::post('/complete', [InquiryController::class, 'complete'])->name('complete');
        });

        // ログアウト
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});
