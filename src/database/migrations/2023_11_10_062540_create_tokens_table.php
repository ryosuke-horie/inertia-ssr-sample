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
        Schema::create('tokens', function (Blueprint $table) {
            $table->comment('トークン管理');

            // IDや認証に関連するカラム
            $table->text('token')->default(true)->comment('トークン');
            $table->unsignedBigInteger('entity_id')->comment('利用者ID: 投げ銭ユーザー/スタッフID/事業者申込申請ID/法人申込申請ID');
            $table->smallInteger('entity_type')->comment('利用者タイプID: 1:投げ銭ユーザー 2:スタッフ 3:事業者 4:法人');
            $table->string('email', 2048)->comment('メールアドレス');
            $table->timestamp('end_at')->nullable()->comment('有効期限');

            // タイムスタンプ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokens');
    }
};
