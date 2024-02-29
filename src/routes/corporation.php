<?php

use App\Http\Controllers\Corporation\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Corporation\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Corporation\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Corporation\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Corporation\Auth\NewPasswordController;
use App\Http\Controllers\Corporation\Auth\PasswordController;
use App\Http\Controllers\Corporation\Auth\PasswordResetLinkController;
use App\Http\Controllers\Corporation\Auth\RegisteredUserController;
use App\Http\Controllers\Corporation\Auth\VerifyEmailController;
use App\Http\Controllers\Corporation\BusinessOperatorController;
use App\Http\Controllers\Corporation\DepositDetailsController;
use App\Http\Controllers\Corporation\MediaCheckController;
use App\Http\Controllers\Corporation\MypageController;
use App\Http\Controllers\Corporation\NewEmailController;
use App\Http\Controllers\Corporation\NotificationController;
use App\Http\Controllers\Corporation\PointController;
use App\Http\Controllers\Corporation\ProfileController;
use App\Http\Controllers\Corporation\SettingController;
use App\Http\Controllers\Corporation\TransferController;
use App\Http\Controllers\Inquiry\InquiryController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('corporation')->name('corporation.')->group(function () {
    Route::prefix('change-email')->name('change-email.')->group(function () {
        Route::get('/', [NewEmailController::class, 'check'])->name('check');
        Route::get('/complete', [NewEmailController::class, 'complete'])->name('complete');
    });

    // ゲストルーティング
    Route::middleware('corporation.guest:corporation')->group(function () {
        Route::get('register', [RegisteredUserController::class, 'create'])
            ->name('register');

        Route::post('register', [RegisteredUserController::class, 'store']);

        Route::get('login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('password.request');

        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
            ->name('password.reset');

        Route::post('reset-password', [NewPasswordController::class, 'store'])
            ->name('password.store');
    });

    // 認証済ルーティング
    Route::middleware('corporation:corporation')->group(function () {
        Route::get('verify-email', EmailVerificationPromptController::class)
            ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');

        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->name('password.confirm');

        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

        Route::put('password', [PasswordController::class, 'update'])->name('password.update');

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

        Route::get('/dashboard', function () {
            return Inertia::render('Corporation/Dashboard');
        })->name('dashboard');

        // マイページ
        Route::prefix('mypage')->name('mypage.')->group(function () {
            // 初期表示
            Route::get('/', [MypageController::class, 'index'])->name('index');
        });

        // プロフィール
        Route::prefix('profile')->name('profile.')->group(function () {
            // 初期画面
            Route::get('/', [ProfileController::class, 'edit'])->name('edit');
            // メールアドレス変更
            Route::prefix('change-email')->name('change-email.')->group(function () {
                Route::get('/', [ProfileController::class, 'showChangeEmail'])->name('show');
                Route::put('/', [ProfileController::class, 'emailCreate'])->name('create');
            });
            // パスワード変更
            Route::prefix('change-password')->name('change-password.')->group(function () {
                Route::get('/', [ProfileController::class, 'showChangePassword'])->name('show');
                Route::put('/', [ProfileController::class, 'passwordUpdate'])->name('update');
            });
        });

        // お知らせ
        Route::prefix('notification')->name('notification.')->group(function () {
            //　一覧表示
            Route::get('/index', [NotificationController::class, 'index'])->name('index');

            //　お知らせ詳細表示
            Route::get('/{notificationId}', [NotificationController::class, 'show'])->name('show');

            //　個人向けお知らせ詳細表示
            Route::get('/private/{notificationId}', [NotificationController::class, 'showPrivate'])->name('show.private');

            // PDF出力
            Route::get('/pdf/{notificationId}', [NotificationController::class, 'pdf'])->name('pdf');
        });

        // 振込情報
        Route::prefix('transfer')->name('transfer.')->group(function () {
            // 選択画面表示
            Route::get('/', [TransferController::class, 'select'])->name('select');
            // 振込申請画面表示
            Route::get('/application', [TransferController::class, 'application'])->name('application');
            // 振込申請更新
            Route::put('/application/update/{settingId}', [TransferController::class, 'updateApplication'])->name('application.update');
            // 口座情報画面表示
            Route::get('/bank-account', [TransferController::class, 'bankAccount'])->name('bank-account');
            // 口座情報更新
            Route::post('/bank-accont/update', [TransferController::class, 'bankAccountUpdate'])->name('bank-account.update');
            // 支払通知書画面表示
            Route::get('/payment-advice', [TransferController::class, 'paymentAdvice'])->name('payment-advice');
        });

        // 各種設定
        Route::prefix('setting')->name('setting.')->group(function () {
            // 選択画面表示
            Route::get('/', [SettingController::class, 'index'])->name('index');
            // 公開設定表示
            Route::get('/publish', [SettingController::class, 'publish'])->name('publish');
        });

        // ポイント集計
        Route::prefix('point')->name('point.')->group(function () {
            // 一覧
            Route::get('/', [PointController::class, 'index'])->name('index');
            // 事業者一覧
            Route::get('/{yearMonth}', [PointController::class, 'businessOperator'])->name('business-operator');
            // スタッフ一覧
            Route::get('/{yearMonth}/{businessId}', [PointController::class, 'staff'])->name('staff');
        });

        // 店舗
        Route::prefix('business-operator')->name('business-operator.')->group(function () {
            // 選択画面
            Route::get('/', [BusinessOperatorController::class, 'index'])->name('index');
            // 店舗管理
            Route::prefix('management')->name('management.')->group(function () {
                // 店舗管理一覧
                Route::get('/', [BusinessOperatorController::class, 'managementIndex'])->name('index');
                // 新規登録画面
                Route::get('/create', [BusinessOperatorController::class, 'managementCreate'])->name('create');
                // 新規登録確認画面
                Route::post('/create/confirm', [BusinessOperatorController::class, 'managementConfirm'])->name('confirm');
                // 新規登録処理
                Route::post('/create/confirm/store', [BusinessOperatorController::class, 'managementStore'])->name('store');
                // 店舗詳細
                Route::get('/{businessApplicationId}', [BusinessOperatorController::class, 'managementShow'])->name('show');
                // 登録情報
                Route::get('/{businessApplicationId}/info', [BusinessOperatorController::class, 'managementInfo'])->name('info');
                // 公開設定
                Route::get('/{businessApplicationId}/publish/{businessId}', [BusinessOperatorController::class, 'managementPublish'])->name('publish');
            });
        });

        // 投稿動画・画像確認
        Route::prefix('media-check')->name('media-check.')->group(function () {
            // 事業者一覧
            Route::get('/', [MediaCheckController::class, 'index'])->name('index');
            // 事業者メディア詳細
            Route::get('/{businessId}', [MediaCheckController::class, 'show'])->name('show');
            // 削除
            Route::delete('/', [MediaCheckController::class, 'delete'])->name('delete');
        });

        // 売上入金明細
        Route::prefix('deposit-details')->name('deposit-details.')->group(function () {
            // 年月一覧
            Route::get('/', [DepositDetailsController::class, 'index'])->name('index');
            // 事業者一覧
            Route::get('/{requestId}', [DepositDetailsController::class, 'business'])->name('business');
            // スタッフ一覧
            Route::get('/{requestId}/{businessId}', [DepositDetailsController::class, 'staff'])->name('staff');
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

    });
});
