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
        Schema::create('staff', function (Blueprint $table) {
            $table->comment('スタッフ会員情報');

            // IDや認証に関連するカラム
            $table->increments('staff_id')->comment('スタッフID');
            $table->string('email', 2048)->nullable()->unique()->comment('メールアドレス: 登録は任意（動物などを考慮）、暗号化');
            $table->string('email_hash', 2048)->nullable()->comment('検索用メールアドレス: ハッシュ化');
            $table->string('password', 512)->nullable()->comment('パスワード');
            $table->rememberToken()->comment('持続ログイントークン');
            // ユーザー情報に関連するカラム
            $table->integer('business_id')->comment('事業者ID');
            $table->string('real_name', 50)->comment('本名: 事業者が認識できるように登録した時点のスタッフ名を登録。内部的に保持するため入力不要。編集不可。');
            $table->string('staff_name', 50)->comment('スタッフ名: 編集可能');
            $table->string('comment', 100)->nullable()->comment('一言コメント');

            // 設定や状態に関連するフラグなど
            $table->integer('points')->default(0)->comment('保有ポイント数');
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
        Schema::dropIfExists('staff');
    }
};
