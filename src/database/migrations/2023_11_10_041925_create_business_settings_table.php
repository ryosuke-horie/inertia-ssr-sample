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
        Schema::create('business_settings', function (Blueprint $table) {
            $table->comment('事業者設定');

            // IDや認証に関連するカラム
            $table->increments('setting_id')->comment('事業者設定ID');

            // ユーザー情報に関連するカラム
            $table->integer('business_id')->comment('事業者ID');

            // 設定や状態に関連するフラグなど
            $table->boolean('is_publish')->default(false)->comment('公開設定: TRUE:公開 FALSE:非公開, 事業者のみ設定可能');
            $table->boolean('is_media_publish')->default(false)->comment('動画・画像公開設定: TRUE:公開 FALSE:非公開, 事業者のみ設定可能');
            $table->boolean('is_review_publish')->default(false)->comment('口コミ公開設定: TRUE:公開 FALSE:非公開, 事業者のみ設定可能');
            $table->boolean('is_staff_ranking_publish')->default(false)->comment('業者内スタッフランキング公開設定: TRUE:公開 FALSE:非公開, 事業者のみ設定可能');
            $table->boolean('is_auto_apply')->default(false)->comment('自動申請設定: TRUE:自動申請 FALSE:申請なし, 法人も設定可能');
            $table->integer('auto_apply_amount')->default(2000)->comment('自動申請設定金額: 2000円以上、法人も設定可能');

            // タイムスタンプ
            $table->timestamps();

            // 外部キー制約を設定
            $table->foreign('business_id')->references('business_id')->on('business_operators')->onDelete('restrict'); // 親レコードを削除しようとするとエラーが発生する
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_settings');
    }
};
