<?php

use App\Http\Controllers\ChatGPT\ChatGPTController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\ProfileController;
use App\Http\Controllers\BusinessOperator\QRController;
use App\Http\Controllers\BusinessOperator\TransferController;
use App\Http\Controllers\BusinessOperator\SettingController;
use App\Http\Controllers\User\BusinessOperatorController;
use App\Http\Controllers\User\StaffController as UserStaffController;
use App\Http\Controllers\User\PaymentInfoController;
use App\Http\Controllers\BusinessOperator\AdminStaff\ApiAdminStaffController;
use App\Http\Controllers\BusinessOperator\Staff\ApiStaffController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\BusinessOperator\ProfileController as BusinessOperatorProfileController;
use App\Http\Controllers\GuestUser\StaffController;
use App\Http\Controllers\Corporation\TransferController as CorporationTransferController;
use App\Http\Controllers\Corporation\SettingController as CorporationSettingController;
use App\Http\Controllers\User\PointChargeController;
use App\Http\Controllers\Zipcloud\ZipcloudController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('all')->group(function () {
    Route::get('{staffId}/prompts/{promptId}', [ChatGPTController::class, 'userChatGPT'])->name('api.staff.chatgpt.get');
    Route::prefix('tips')->name('api.tips.')->group(function () {
        Route::get('{tipId}/prompts/{promptId}', [ChatGPTController::class, 'tipChatGPT'])->name('chatgpt.get');
    });
    Route::get('zipcloud/{zipCode}', [ZipcloudController::class, 'getAddress'])->name('zipcloud');
});

/**
 * スタッフ用API
 */
Route::middleware('staff:staff')->group(function () {
    Route::prefix('staff')->name('api.staff.')->group(function () {
        Route::post('profile/image', [ProfileController::class, 'updateProfileImage'])->name('update.profile.image');
        Route::delete('profile/image', [ProfileController::class, 'deleteProfileImage'])->name('delete.profile.image');
        Route::post('setting/message-notified', [ProfileController::class, 'updateSettingMessageNotified'])->name("update.message.notified");
    });
});


/**
 * 事業者用API
 */
Route::middleware('business-operator:business-operator')->group(function () {
    Route::prefix('business-operator')->name('api.business-operator.')->group(function () {
        // スタッフQRコード生成
        Route::post('qr/staff', [QRController::class, 'createStaff'])->name('create-staff');
        // ファイル削除
        Route::delete('file', [QRController::class, 'deleteFile'])->name('delete-file');
        // ショップQRコード生成
        Route::post('qr/business-operator', [QRController::class, 'createBusinessOperator'])->name('create-business-operator');
        // 支払通知書PDF生成
        Route::post('transfer/payment-advice/pdf', [TransferController::class, 'paymentAdvicePdf'])->name('payment-advice-pdf');
        // スタッフ管理
        Route::prefix('staff')->name('staff.')->group(function () {
            Route::get('/', [ApiStaffController::class, 'index'])->name('index');
            // 管理者スタッフ
            Route::prefix('admin')->name('admin-staff.')->group(function () {
                Route::middleware('api.business-operator.admin-staff.adminStaffId')->group(function () {
                    Route::delete('{adminStaffId}', [ApiAdminStaffController::class, 'delete'])->name('delete');
                });
            });
            // スタッフ
            Route::prefix('{staffId}')->group(function () {
                Route::middleware('api.business-operator.staff.staffId')->group(function () {
                    Route::delete('/', [ApiStaffController::class, 'delete'])->name('delete');
                    Route::post('/profile/image', [ApiStaffController::class, 'updateProfileImage'])->name('update.profile.image');
                    Route::delete('/profile/image', [ApiStaffController::class, 'deleteProfileImage'])->name('delete.profile.image');
                    Route::post('/setting/message-notified', [ApiStaffController::class, 'updateSettingMessageNotified'])->name("update.message.notified");
                    Route::get('/schedules', [ApiStaffController::class, 'getStaffSchedules'])->name('schedules');
                    Route::post('/schedules', [ApiStaffController::class, 'updateStaffSchedules'])->name('update.schedules');
                });
            });
        });

        // 各種設定
        Route::prefix('setting')->name('setting.')->group(function () {
            // 公開設定更新
            Route::put('/publish', [SettingController::class, 'updatePublish'])->name('update.publish');
            // 口コミ公開設定更新
            Route::put('/review-publish', [SettingController::class, 'updateReviewPublish'])->name('update.review-publish');
            // スタッフランキング公開設定更新
            Route::put('/staff-ranking-publish', [SettingController::class, 'updateStaffRankingPublish'])->name('update.staff-ranking-publish');
        });

        // プロフィール
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::post('image', [BusinessOperatorProfileController::class, 'updateProfileImage'])->name('update.image');
            Route::delete('image', [BusinessOperatorProfileController::class, 'deleteProfileImage'])->name('delete.image');
        });
    });
});

/**
 * 法人用API
 */
Route::middleware('corporation:corporation')->prefix('corporation')->name('api.corporation.')->group(function () {
    // 支払通知書PDF生成
    Route::post('transfer/payment-advice/pdf', [CorporationTransferController::class, 'paymentAdvicePdf'])->name('payment-advice-pdf');
    // 公開設定更新
    Route::put('setting/publish', [CorporationSettingController::class, 'updatePublish'])->name('update.publish');
});

/**
 * 投げ銭ユーザー用API
 */
Route::middleware('user:user')->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::post('profile/image', [UserProfileController::class, 'updateUserProfileImage'])->name('update.profile.image');
        Route::post('profile/setting/show-ranking', [UserProfileController::class, 'updateSettingShowRanking'])->name('update.profile.show.setting');

        Route::prefix('business-operator')->name('api.business-operator.')->group(function () {
            // 口コミ削除
            Route::delete('/{businessId}/review/{reviewId}', [BusinessOperatorController::class, 'deleteReview'])->name('delete-review');
        });

        Route::prefix('staff')->name('api.staff.')->group(function () {
            // お気に入りスタッフ切り替え
            Route::post('/{staffId}/staff-favorite', [UserStaffController::class, 'toggleFavorite'])->name('toggle-favorite');
            // いいね切り替え
            Route::post('/{staffId}/user-like', [UserStaffController::class, 'toggleUserLike'])->name('user-like');
        });

        // 支払い情報
        Route::prefix('payment-info')->name('api.payment-info.')->group(function () {
            // stripe API関連
            Route::post('/create-payment-intent', [PointChargeController::class, 'createPaymentIntent'])->name('create-payment-intent');
            Route::post('/get-setup-intent', [PaymentInfoController::class, 'getSetupIntent'])->name('get-setup-intent');
        });
    });
});

// ゲスト投げ銭ユーザー
Route::prefix('guest')->name('guest.')->group(function () {
    Route::prefix('business-operator/{businessId}')->group(function () {
        Route::middleware('api.guest.business-operator.businessId')->group(function () {
            Route::prefix('staff/{staffId}')->group(function () {
                Route::middleware('api.guest.business-operator.businessId.staff.staffId')->group(function () {
                    Route::get('/exists', [StaffController::class,'exists'])->name('exists');
                    Route::post('/create-payment-intent', [StaffController::class, 'createPaymentIntent'])->name('create-payment-intent');
                });
            });
        });
    });
});
