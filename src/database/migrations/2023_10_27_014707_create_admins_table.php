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
        Schema::create('admins', function (Blueprint $table) {
            $table->comment('管理者会員情報');

            // IDや認証に関連するカラム
            $table->increments('admin_id')->comment('管理者ID');
            $table->string('name', 50)->comment('管理者名');
            $table->string('password', 512)->comment('パスワード');

            // 設定や状態に関連するフラグなど
            $table->integer('role')->default(1)->comment('権限: 1:基本権限');

            // タイムスタンプ
            $table->softDeletes()->comment('削除日時');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
