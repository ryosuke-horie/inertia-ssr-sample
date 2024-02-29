<?php

use App\Http\Controllers\Inquiry\InquiryController;
use App\Http\Controllers\Stripe\WebhookController;
use App\Http\Controllers\UploadTestController;
use App\Http\Controllers\User\ReviewGuideLineController;
use App\Http\Controllers\WebController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [WebController::class, 'welcome'])->name('welcome');
Route::get('/owner', [WebController::class, 'owner'])->name('owner');
Route::get('/faq', [WebController::class, 'faq'])->name('faq');
Route::get('/privacy', [WebController::class, 'privacy'])->name('privacy');
Route::get('/law', [WebController::class, 'law'])->name('law');
Route::get('/term', [WebController::class, 'term'])->name('term');
Route::get('/revuew-guide-line', [ReviewGuideLineController::class, 'index'])->name('revuew-guide-line');

// お問い合わせ
Route::prefix('inquiry')->name('inquiry.')->group(function () {
    // 初期画面表示
    Route::get('/', [InquiryController::class, 'index'])->name('index');
    // 確認画面表示
    Route::post('/confirm', [InquiryController::class, 'confirm'])->name('confirm');
    // 送信完了処理
    Route::post('/complete', [InquiryController::class, 'complete'])->name('complete');
});

// S3動画アップロードテスト用
Route::get('upload', [UploadTestController::class, 'index'])->name('upload.get');
Route::post('upload', [UploadTestController::class, 'store'])->name('upload.post');

// stripeのルーティング
Route::prefix('stripe')->name('stripe.')->group(function () {
    // 支払い情報登録
    Route::post('/webhook', [WebhookController::class, 'handleWebhook'])->name('webhook');
});

// 投げ銭ユーザ用のルーティング
require __DIR__ . '/guest-user.php';
require __DIR__ . '/user.php';

// 管理者（MITS）用のルーティング
require __DIR__ . '/admin.php';

// 管理者スタッフ用のルーティング
require __DIR__ . '/admin-staff.php';

// スタッフ用のルーティング
require __DIR__ . '/staff.php';

// 事業者用のルーティング
require __DIR__ . '/business-operator.php';

// 法人用のルーティング
require __DIR__ . '/corporation.php';

// 404ページ
Route::get('/{any}', [WebController::class, 'notFound'])->where('any', '.*');
