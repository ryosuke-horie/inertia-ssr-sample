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
        Schema::create('corporations', function (Blueprint $table) {
            $table->comment('法人会員情報');

            // IDや認証に関連するカラム
            $table->increments('corporation_id')->comment('法人ID');
            $table->string('email', 2048)->unique()->comment('法人メールアドレス: 暗号化');
            $table->string('email_hash', 2048)->nullable()->comment('検索用メールアドレス: ハッシュ化');
            $table->string('password', 512)->comment('法人パスワード');
            $table->rememberToken()->comment('持続ログイントークン');

            // ユーザー情報に関連するカラム
            $table->integer('corporation_application_id')->nullable()->comment('法人申込申請ID');
            $table->string('corporation_name', 100)->comment('法人名');
            $table->string('zip_code', 7)->comment('郵便番号: zipcloud利用');
            $table->string('pref_code', 2)->comment('都道府県コード: 定数ファイル参照(1~47)');
            $table->string('city', 200)->comment('市区町村');
            $table->string('phone', 255)->comment('法人電話番号: 暗号化');
            $table->string('invoice', 255)->comment('適格請求書発行事業者登録番号: 暗号化');
            $table->text('notes')->nullable()->comment('備考');
            // 論理削除されていれば NULL， されていなければ ture になる生成列を定義
            $table->boolean('exist')->nullable()->storedAs('case when deleted_at is null then true else false end')->comment('メールアドレス複合ユニーク制約用');

            // タイムスタンプ
            $table->softDeletes('deleted_at')->comment('削除日時');
            $table->timestamps();

            // 複合ユニーク制約
            $table->unique(['email', 'exist']);

            // 外部キー制約
            $table->foreign('corporation_application_id')->references('corporation_application_id')->on('corporation_applications')->onDelete('restrict'); // 親レコードを削除しようとするとエラーが発生する
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corporations');
    }
};
