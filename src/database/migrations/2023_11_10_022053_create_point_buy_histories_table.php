<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('point_buy_histories', function (Blueprint $table) {
            $table->comment('ポイント購入履歴');

            // IDや認証に関連するカラム
            $table->increments('buy_id')->comment('ポイント購入履歴ID');

            // ユーザー情報に関連するカラム
            $table->integer('user_id')->comment('投げ銭ユーザーID');

            // Stripe PaymentIntentId
            $table->string('payment_intent_id', 100)->comment('stripe決済番号');
            // 設定や状態に関連するフラグなど
            $table->integer('buy_points')->comment('購入ポイント数');
            $table->decimal('amount', 10, 2)->comment('引き落とし金額');
            $table->integer('remaining_points')->nullable()->comment('残りポイント数');
            $table->boolean('is_paid')->comment('有償・無償フラグ: TRUE:有償、FALSE:無償');

            // タイムスタンプ
            $table->timestamp('expiration_date')->comment('有効期限');
            $table->timestamps();

            // 外部キー制約の設定
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('restrict'); // 親レコードを削除しようとするとエラーが発生する
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_buy_histories');
    }
};
