<?php

use App\Http\Controllers\Inquiry\InquiryController;
use App\Http\Controllers\Staff\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Staff\Auth\NewPasswordController;
use App\Http\Controllers\Staff\Auth\PasswordResetLinkController;
use App\Http\Controllers\Staff\Auth\RegisteredUserController;
use App\Http\Controllers\Staff\ChangeEmailController;
use App\Http\Controllers\Staff\ChangePasswordController;
use App\Http\Controllers\Staff\LikeController;
use App\Http\Controllers\Staff\MypageController;
use App\Http\Controllers\Staff\NewEmailController;
use App\Http\Controllers\Staff\NotificationController;
use App\Http\Controllers\Staff\PointHistoryController;
use App\Http\Controllers\Staff\ProfileController;
use App\Http\Controllers\Staff\TipController;
use Illuminate\Support\Facades\Route;

Route::prefix('staff')->name('staff.')->group(function () {
    Route::prefix('change-email')->name('change-email.')->group(function () {
        Route::get('/', [NewEmailController::class, 'check'])->name('check');
        Route::get('/complete', [NewEmailController::class, 'complete'])->name('complete');
    });

    // ゲストルーティング
    Route::middleware('staff.guest:staff')->group(function () {
        // 新規登録画面
        Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('register', [RegisteredUserController::class, 'store'])->name('create');
        // ログイン画面
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
        // パスワード変更画面
        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
    });

    // 認証済ルーティング
    Route::middleware('staff:staff')->group(function () {
        // マイページ
        Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');

        // プロフィール
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'show'])->name('show');
            Route::patch('/', [ProfileController::class, 'update'])->name('update');
            // メールアドレス変更
            Route::prefix('change-email')->name('change-email.')->group(function () {
                Route::get('/', [ChangeEmailController::class, 'show'])->name('show');
                Route::post('/', [ChangeEmailController::class, 'create'])->name('create');
            });
            // パスワード変更
            Route::prefix('change-password')->name('change-password.')->group(function () {
                Route::get('/', [ChangePasswordController::class, 'show'])->name('show');
                Route::put('/', [ChangePasswordController::class, 'update'])->name('update');
            });
            Route::get('/setting', [ProfileController::class, 'showSetting'])->name('show.setting');
        });

        // お知らせ
        Route::prefix('notification')->name('notification.')->group(function () {
            //　一覧表示
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            //　お知らせ詳細表示
            Route::middleware('staff.notificationId')->group(function () {
                Route::get('/{notificationId}', [NotificationController::class, 'show'])->name('show');
                //　個人向けお知らせ詳細表示
                Route::get('/private/{notificationId}', [NotificationController::class, 'showPrivate'])->name('show.private');
                // PDF出力
                Route::get('/pdf/{notificationId}', [NotificationController::class, 'pdf'])->name('pdf');
            });
        });

        // いいね
        Route::prefix('like')->name('like.')->group(function () {
            Route::get('/', [LikeController::class, 'index'])->name('index');
        });

        // ポイント履歴
        Route::prefix('point-history')->name('pointHistory.')->group(function () {
            Route::get('/', [PointHistoryController::class, 'index'])->name('index');
        });

        // 投げ銭
        Route::prefix('tips')->name('tips.')->group(function () {
            Route::get('/', [TipController::class, 'index'])->name('index');
            Route::middleware('staff.tipId')->group(function () {
                Route::get('/{tipId}', [TipController::class, 'show'])->name('show');
                Route::post('/{tipId}', [TipController::class, 'create'])->name('create');
                Route::middleware('staff.tipId.replyId')->group(function () {
                    Route::delete('/{tipId}/replies/{replyId}', [TipController::class, 'delete'])->name('delete');
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
