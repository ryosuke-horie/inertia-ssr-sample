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
        Schema::create('corporation_settings', function (Blueprint $table) {
            $table->comment('法人設定');

            // IDや認証に関連するカラム
            $table->increments('setting_id')->comment('法人設定ID');

            // ユーザー情報に関連するカラム
            $table->integer('corporation_id')->comment('法人ID');

            // 設定や状態に関連するフラグなど
            $table->boolean('is_auto_apply')->default(false)->comment('自動申請設定: TRUE:自動申請 FALSE:申請なし, 法人も設定可能');
            $table->integer('auto_apply_amount')->default(2000)->comment('自動申請設定金額: 2000円以上、法人も設定可能');

            // タイムスタンプ
            $table->timestamps();

            // 外部キー制約を設定
            $table->foreign('corporation_id')->references('corporation_id')->on('corporations')->onDelete('restrict'); // 親レコードを削除しようとするとエラーが発生する
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corporation_settings');
    }
};
