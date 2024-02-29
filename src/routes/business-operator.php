<?php

use App\Http\Controllers\BusinessOperator\AdminStaff\AdminStaffChangeEmailController;
use App\Http\Controllers\BusinessOperator\AdminStaff\AdminStaffController;
use App\Http\Controllers\BusinessOperator\AdminStaff\AdminStaffRegisterdController;
use App\Http\Controllers\BusinessOperator\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BusinessOperator\Auth\ConfirmablePasswordController;
use App\Http\Controllers\BusinessOperator\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\BusinessOperator\Auth\EmailVerificationPromptController;
use App\Http\Controllers\BusinessOperator\Auth\NewPasswordController;
use App\Http\Controllers\BusinessOperator\Auth\PasswordController;
use App\Http\Controllers\BusinessOperator\Auth\PasswordResetLinkController;
use App\Http\Controllers\BusinessOperator\Auth\RegisteredUserController;
use App\Http\Controllers\BusinessOperator\Auth\VerifyEmailController;
use App\Http\Controllers\BusinessOperator\CancelController;
use App\Http\Controllers\BusinessOperator\DepositDetailsController;
use App\Http\Controllers\BusinessOperator\MediaCheckController;
use App\Http\Controllers\BusinessOperator\MypageController;
use App\Http\Controllers\BusinessOperator\NewEmailController as BusinessOperatorNewEmailController;
use App\Http\Controllers\BusinessOperator\NotificationController;
use App\Http\Controllers\BusinessOperator\PointController;
use App\Http\Controllers\BusinessOperator\ProfileController;
use App\Http\Controllers\BusinessOperator\QRController;
use App\Http\Controllers\BusinessOperator\ReviewController;
use App\Http\Controllers\BusinessOperator\SettingController;
use App\Http\Controllers\BusinessOperator\Staff\StaffChangeEmailController;
use App\Http\Controllers\BusinessOperator\Staff\StaffController;
use App\Http\Controllers\BusinessOperator\Staff\StaffPointHistoryController;
use App\Http\Controllers\BusinessOperator\Staff\StaffRegisterdController;
use App\Http\Controllers\BusinessOperator\Staff\StaffScheduleController;
use App\Http\Controllers\BusinessOperator\Staff\StaffSettingController;
use App\Http\Controllers\BusinessOperator\Staff\StaffTipController;
use App\Http\Controllers\BusinessOperator\TransferController;
use App\Http\Controllers\Inquiry\InquiryController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('business-operator')->name('business-operator.')->group(function () {
    Route::prefix('change-email')->name('change-email.')->group(function () {
        Route::get('/', [BusinessOperatorNewEmailController::class, 'check'])->name('check');
        Route::get('/complete', [BusinessOperatorNewEmailController::class, 'complete'])->name('complete');
    });

    // ゲストルーティング
    Route::middleware('business-operator.guest:business-operator')->group(function () {
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
    Route::middleware('business-operator:business-operator')->group(function () {
        // マイページ
        Route::prefix('mypage')->name('mypage.')->group(function () {
            // 初期表示
            Route::get('/', [MypageController::class, 'index'])->name('index');
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

        // プロフィール
        Route::prefix('profile')->name('profile.')->group(function () {
            // 初期表示
            Route::get('/', [ProfileController::class, 'edit'])->name('edit');
            // プロフィール更新
            Route::patch('/update', [ProfileController::class, 'update'])->name('update');
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

        // スタッフ
        Route::prefix('staff')->name('staff.')->group(function () {
            // 選択画面表示
            Route::get('/select', [StaffController::class, 'select'])->name('select');
            // 一覧画面表示
            Route::get('/', [StaffController::class, 'index'])->name('index');
            // スケジュール画面
            Route::prefix('schedules')->name('schedules.')->group(function () {
                Route::get('/', [StaffScheduleController::class, 'index'])->name('index');
            });
            // 応援履歴
            Route::prefix('tips')->name('tips.')->group(function () {
                Route::get('/', [StaffTipController::class, 'index'])->name('index');
                Route::prefix('{tipId}')->group(function () {
                    Route::middleware('business-operator.staff.tipId')->group(function () {
                        Route::get('/', [StaffTipController::class, 'show'])->name('show');
                        Route::post('/', [StaffTipController::class, 'createTipReply'])->name('create');
                        Route::middleware('business-operator.staff.tipId.replyId')->group(function () {
                            Route::delete('/replies/{replyId}', [StaffTipController::class, 'deleteTipReply'])->name('delete');
                        });
                    });
                });
            });

            // 管理者スタッフ
            Route::prefix('admin')->name('admin.')->group(function () {
                Route::get('/', [AdminStaffController::class, 'index'])->name('index');
                // 管理スタッフ登録画面表示
                Route::prefix('add')->name('add.')->group(function () {
                    Route::get('/', [AdminStaffRegisterdController::class, 'show'])->name('show');
                    Route::post('/', [AdminStaffRegisterdController::class, 'create'])->name('create');
                });
                Route::prefix('{adminStaffId}')->group(function () {
                    Route::middleware('business-operator.adminStaffId')->group(function () {
                        // 管理者スタッフ詳細
                        Route::get('/', [AdminStaffController::class, 'show'])->name('show');
                        Route::put('/', [AdminStaffController::class, 'update'])->name('update');
                        // 管理者メールアドレス変更
                        Route::prefix('change-email')->name('change-email.')->group(function () {
                            Route::get('/', [AdminStaffChangeEmailController::class, 'show'])->name('show');
                            Route::put('/', [AdminStaffChangeEmailController::class, 'update'])->name('update');
                        });
                    });
                });
            });

            // スタッフ登録画面表示
            Route::prefix('add')->name('add.')->group(function () {
                Route::get('/', [StaffRegisterdController::class, 'show'])->name('show');
                Route::post('/', [StaffRegisterdController::class, 'create'])->name('create');
            });

            Route::prefix('{staffId}')->group(function () {
                Route::middleware('business-operator.staffId')->group(function () {
                    // スタッフ詳細画面表示
                    Route::get('/', [StaffController::class, 'show'])->name('show');
                    Route::patch('/', [StaffController::class, 'update'])->name('update');
                    // 各種設定
                    Route::prefix('setting')->name('setting.')->group(function () {
                        Route::get('/', [StaffSettingController::class, 'show'])->name('show');
                    });
                    // ポイント変動履歴
                    Route::prefix('point-history')->name('point-history.')->group(function () {
                        Route::get('/', [StaffPointHistoryController::class, 'index'])->name('index');
                    });
                    // スタッフメールアドレス変更画面
                    Route::prefix('change-email')->name('change-email.')->group(function () {
                        Route::get('/', [StaffChangeEmailController::class, 'show'])->name('show');
                        Route::put('/', [StaffChangeEmailController::class, 'update'])->name('update');
                    });
                });
            });
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

        // QRコード
        Route::prefix('QR')->name('qr.')->group(function () {
            // 一覧表示
            Route::get('/', [QRController::class, 'index'])->name('index');
            // ショップQRコード画面
            Route::get('/business-operator', [QRController::class, 'businessOperator'])->name('business-operator');
            // スタッフQRコード画面
            Route::get('/staff', [QRController::class, 'Staff'])->name('staff');
        });

        // 各種設定
        Route::prefix('setting')->name('setting.')->group(function () {
            // 選択画面表示
            Route::get('/', [SettingController::class, 'index'])->name('index');
            // 公開設定表示
            Route::get('/publish', [SettingController::class, 'publish'])->name('publish');
            // 口コミ公開設定表示
            Route::get('/review-publish', [SettingController::class, 'reviewPublish'])->name('review-publish');
            // スタッフランキング公開設定表示
            Route::get('/staff-ranking-publish', [SettingController::class, 'staffRankingPublish'])->name('staff-ranking-publish');
            // 投げ銭金額設定画面表示
            Route::get('/tip', [SettingController::class, 'tip'])->name('tip');
            Route::post('/tip', [SettingController::class, 'updateTip'])->name('tip.update');
        });

        // ポイント集計
        Route::prefix('point')->name('point.')->group(function () {
            // 一覧
            Route::get('/', [PointController::class, 'index'])->name('index');
            // スタッフ一覧
            Route::get('/{yearMonth}', [PointController::class, 'staff'])->name('staff');
        });

        // 口コミ管理
        Route::prefix('review')->name('review.')->group(function () {
            // 初期画面表示
            Route::get('/', [ReviewController::class, 'index'])->name('index');
            // 削除
            Route::delete('/', [ReviewController::class, 'delete'])->name('delete');
        });

        // 売上入金明細
        Route::prefix('deposit-details')->name('deposit-details.')->group(function () {
            // 一覧
            Route::get('/', [DepositDetailsController::class, 'index'])->name('index');
            // スタッフ一覧
            Route::get('/{requestId}', [DepositDetailsController::class, 'staff'])->name('staff');
        });

        // 投稿動画・画像確認
        Route::prefix('media-check')->name('media-check.')->group(function () {
            // 初期画面表示
            Route::get('/', [MediaCheckController::class, 'index'])->name('index');
            // 削除
            Route::delete('/', [MediaCheckController::class, 'delete'])->name('delete');
        });

        // 退会
        Route::prefix('cancel')->name('cancel.')->group(function () {
            // 初期画面表示
            Route::get('/', [CancelController::class, 'index'])->name('index');
            // 確認画面表示
            Route::get('/confirm', [CancelController::class, 'confirm'])->name('confirm');
            // 削除処理
            Route::delete('/', [CancelController::class, 'delete'])->name('delete');
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

        Route::get('/dashboard', function () {
            return Inertia::render('BusinessOperator/Dashboard');
        })->name('dashboard');

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
    });
});
