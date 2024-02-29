<?php

use App\Http\Controllers\Inquiry\InquiryController;
use App\Http\Controllers\User\Auth\AuthenticatedSessionController;
use App\Http\Controllers\User\Auth\ConfirmablePasswordController;
use App\Http\Controllers\User\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\User\Auth\EmailVerificationPromptController;
use App\Http\Controllers\User\Auth\NewPasswordController;
use App\Http\Controllers\User\Auth\PasswordController;
use App\Http\Controllers\User\Auth\PasswordResetLinkController;
use App\Http\Controllers\User\Auth\RegisteredUserController;
use App\Http\Controllers\User\Auth\VerifyEmailController;
use App\Http\Controllers\User\BusinessOperatorController;
use App\Http\Controllers\User\CancelController;
use App\Http\Controllers\User\ChangeEmailController;
// use App\Http\Controllers\User\WebhookController;
use App\Http\Controllers\User\ChangePasswordController;
use App\Http\Controllers\User\FavoriteStaffController;
use App\Http\Controllers\User\GuestLoginController;
use App\Http\Controllers\User\LoginMethodController;
use App\Http\Controllers\User\MypageController;
use App\Http\Controllers\User\NewEmailController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\PaymentInfoController;
use App\Http\Controllers\User\PointChargeController;
use App\Http\Controllers\User\PointHistoryController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\SignupController;
use App\Http\Controllers\User\StaffController;
use App\Http\Controllers\User\TipController;
use App\Http\Middleware\User\RedirectIfAuthenticatedByAuth0;
use Auth0\Laravel\Controllers\{LoginController, LogoutController, CallbackController};
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Cashier\Http\Controllers\PaymentController;

Route::prefix('user')->name('user.')->group(function () {
    Route::prefix('change-email')->name('change-email.')->group(function () {
        Route::get('/', [NewEmailController::class, 'check'])->name('check');
        Route::get('/complete', [NewEmailController::class, 'complete'])->name('complete');
    });

    Route::group(['middleware' => ['guard:auth0-session']], static function (): void {
        Route::get('/sns-login', LoginController::class)->name('sns-login');
        Route::get('/sns-logout', LogoutController::class)->name('sns-logout');
        Route::get('/callback', CallbackController::class)->name('callback');
    });

    // チアペイ無料会員登録画面
    Route::get('signup', [SignupController::class, 'index'])->name('signup');

    // ログイン方法の選択画面
    Route::get('login-method', [LoginMethodController::class, 'index'])->name('login-method');

    // 新規登録
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register')->middleware(RedirectIfAuthenticatedByAuth0::class);
    Route::post('register', [RegisteredUserController::class, 'store']);

    // ログイン
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // ゲストユーザーでのログイン
    Route::post('guest-login', [GuestLoginController::class, 'guestLogin'])->name('guest-login');

    // 未ログインユーザー
    Route::middleware('user.guest:user')->group(function () {
        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
    });

    // 会員ユーザー
    Route::middleware(['user:user', 'user.access'])->group(function () {
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

        Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');

        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
        Route::put('password', [PasswordController::class, 'update'])->name('password.update');
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // マイページ
        Route::get('mypage', [MypageController::class, 'index'])->middleware(['verified'])->name('mypage.index');

        // プロフィール
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'show'])->name('show');
            Route::patch('/', [ProfileController::class, 'update'])->name('update');
            Route::prefix('setting')->name('setting.')->group(function () {
                Route::get('/', [ProfileController::class, 'showSetting'])->name('show');
            });
            // スタッフメールアドレス変更画面
            Route::prefix('change-email')->name('change-email.')->group(function () {
                Route::get('/', [ChangeEmailController::class, 'show'])->name('show');
                Route::put('/', [ChangeEmailController::class, 'create'])->name('create');
            });
            Route::prefix('change-password')->name('change-password.')->group(function () {
                Route::get('/', [ChangePasswordController::class, 'show'])->name('show');
                Route::put('/', [ChangePasswordController::class, 'update'])->name('update');
            });
        });

        // 支払い情報
        Route::prefix('payment-info')->name('payment-info.')->group(function () {
            Route::get('/', [PaymentInfoController::class, 'showPaymentMethod'])->name('show');
            // 登録表示
            Route::get('/create', [PaymentInfoController::class, 'createPaymentMethod'])->name('create');
            // 確認表示
            Route::post('/confirm', [PaymentInfoController::class, 'confirmPaymentMethod'])->name('confirm');
            // 登録処理
            Route::post('/register', [PaymentInfoController::class, 'registerPaymentMethod'])->name('register');
            // 削除処理
            Route::delete('/delete', [PaymentInfoController::class, 'deletePaymentMethod'])->name('delete');
        });

        // お知らせ
        Route::prefix('notification')->name('notification.')->group(function () {
            // 一覧表示
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            // 詳細表示
            Route::get('/{notificationId}', [NotificationController::class, 'show'])->name('show');
            // 個人向けお知らせ詳細表示
            Route::get('/private/{notificationId}', [NotificationController::class, 'showPrivate'])->name('show.private');
            // PDF出力
            Route::get('/pdf/{notificationId}', [NotificationController::class, 'pdf'])->name('pdf');
        });

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
            // スタッフへのチップ
            Route::post('/{businessId}/staff/{staffId}', [StaffController::class, 'userTipStore'])->name('staff.userTip.store');
        });

        // ポイント履歴
        Route::prefix('point-history')->name('pointHistory.')->group(function () {
            Route::get('/', [PointHistoryController::class, 'index'])->name('index');
            Route::get('/point', [PointHistoryController::class, 'pointIndex'])->name('point.index');
            Route::get('/expired', [PointHistoryController::class, 'expiredIndex'])->name('expired.index');
        });

        // 投げ銭
        Route::prefix('tips')->name('tips.')->group(function () {
            Route::get('/', [TipController::class, 'index'])->name('index');
            Route::middleware('user.tipId')->group(function () {
                Route::get('/{tipId}', [TipController::class, 'show'])->name('show');
            });
        });

        // お気に入りスタッフ
        Route::prefix('favorite-staff')->name('favorite-staff.')->group(function () {
            // 一覧表示
            Route::get('/', [FavoriteStaffController::class, 'index'])->name('index');
        });

        // ポイント購入
        Route::prefix('point-charge')->name('point-charge.')->group(function () {
            // 一覧表示
            Route::get('/', [PointChargeController::class, 'index'])->name('index');
            // 支払い方法選択
            Route::post('/payment-select', [PointChargeController::class, 'paymentSelect'])->name('payment-select');
            // 支払い完了
            Route::post('/payment-complete', [PointChargeController::class, 'paymentComplete'])->name('payment-complete');
            Route::post('/payment-complete-exist-card', [PointChargeController::class, 'paymentCompleteExistCard'])->name('payment-complete-exist-card');
            // GETリクエストの場合に一覧表示画面に遷移
            Route::get('/payment-select', [PointChargeController::class, 'handleGetOnPayment'])->name('handle-get-payment-select');
            Route::get('/payment-complete', [PointChargeController::class, 'handleGetOnPayment'])->name('handle-get-payment-complete');
            Route::get('/payment-complete-exist-card', [PointChargeController::class, 'handleGetOnPayment'])->name('handle-get-payment-complete');
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
    });
});
