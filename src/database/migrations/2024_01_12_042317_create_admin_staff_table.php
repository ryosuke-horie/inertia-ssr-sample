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
        Schema::create('admin_staff', function (Blueprint $table) {
            $table->comment('管理者スタッフ会員情報');

            // IDや認証に関連するカラム
            $table->increments('admin_staff_id')->comment('管理者スタッフID');
            $table->string('email', 2048)->unique()->comment('メールアドレス:暗号化');
            $table->string('email_hash', 2048)->nullable()->comment('検索用メールアドレス: ハッシュ化');
            $table->string('password', 512)->nullable()->comment('パスワード');
            $table->rememberToken()->comment('持続ログイントークン');

            // ユーザー情報に関連するカラム
            $table->integer('business_id')->comment('事業者ID');
            $table->string('name', 50)->comment('管理者スタッフ名: 編集可能');

            // 論理削除されていれば NULL， されていなければ ture になる生成列を定義
            $table->boolean('exist')->nullable()->storedAs('case when deleted_at is null then true else false end')->comment('メールアドレス複合ユニーク制約用');

            // タイムスタンプ
            $table->softDeletes('deleted_at')->comment('削除日時');
            $table->timestamps();

            // 複合ユニーク制約
            $table->unique(['email', 'exist']);

            // 外部キー制約の設定
            $table->foreign('business_id')->references('business_id')->on('business_operators')->onDelete('restrict'); // 親レコードを削除しようとするとエラーが発生する
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_staff');
    }
};
