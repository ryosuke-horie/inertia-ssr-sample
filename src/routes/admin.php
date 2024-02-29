<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\BusinessOperatorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StaffRankingController;
use App\Http\Controllers\Admin\UserRankingController;
use App\Http\Controllers\Admin\CorporationController;

Route::prefix('mits-admin')->name('mits-admin.')->group(function () {
    Route::middleware('admin.guest:admin')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store']);
    });

    Route::middleware('admin:admin')->group(function () {

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
        // ダッシュボード
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // 事業者管理
        Route::prefix('business-operator')->name('business-operator.')->group(function () {
            // 事業者一覧
            Route::get('/', [BusinessOperatorController::class, 'index'])->name('index');
            // 事業者登録
            Route::get('/create', [BusinessOperatorController::class, 'create'])->name('create');
            Route::post('/create', [BusinessOperatorController::class, 'store'])->name('store');
            // 事業者詳細
            Route::get('/{businessId}/show', [BusinessOperatorController::class, 'show'])->name('show');
            Route::patch('/{businessId}/show', [BusinessOperatorController::class, 'update'])->name('update');
            // 事業者削除
            Route::get('/{businessId}/delete', [BusinessOperatorController::class, 'delete'])->name('delete');
            Route::delete('/{businessId}/delete', [BusinessOperatorController::class, 'destroy'])->name('destroy');

            // 口コミ一覧
            Route::get('/{businessId}/review', [BusinessOperatorController::class, 'reviewIndex'])->name('review.index');
            // 口コミ削除
            Route::delete('/{businessId}/review/{reviewId}', [BusinessOperatorController::class, 'reviewDestroy'])->name('review.destroy');
            // 口コミ詳細
            Route::get('/{businessId}/review/{reviewId}', [BusinessOperatorController::class, 'reviewShow'])->name('review.show');
        });

        // 法人管理
        Route::prefix('corporation')->name('corporation.')->group(function () {
            // 法人一覧Corporation
            Route::get('/', [CorporationController::class, 'index'])->name('index');
            // 法人登録
            Route::get('/create', [CorporationController::class, 'create'])->name('create');
            Route::post('/create', [CorporationController::class, 'store'])->name('store');
            // 法人詳細
            Route::get('/{corporationId}/show', [CorporationController::class, 'show'])->name('show');
            Route::patch('/{corporationId}/show', [CorporationController::class, 'update'])->name('update');
            // 法人削除
            Route::get('/{corporationId}/delete', [CorporationController::class, 'delete'])->name('delete');
            Route::delete('/{corporationId}/delete', [CorporationController::class, 'destroy'])->name('destroy');
        });

        // スタッフランキング
        Route::get('/staff-ranking/{yearDate?}', [StaffRankingController::class, 'index'])->name('staff-ranking');

        // 投げ銭ユーザーランキング
        Route::get('/user-ranking/{yearDate?}', [UserRankingController::class, 'index'])->name('user-ranking');
    });
});
