<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->comment('サブスクリプション情報: ユーザーのサブスクリプション（定期購入）情報を管理');

            $table->bigIncrements('id')->comment('サブスクリプションID');
            $table->unsignedBigInteger('user_id')->comment('ユーザーID: ユーザーテーブルの外部キー');
            $table->string('name')->comment('サブスクリプション名');
            $table->string('stripe_id')->unique()->comment('StripeのサブスクリプションID: Stripeとの統合に使用');
            $table->string('stripe_status')->comment('Stripeのサブスクリプションステータス');
            $table->string('stripe_price')->nullable()->comment('Stripeの価格ID: サブスクリプションの価格');
            $table->integer('quantity')->nullable()->comment('購入数量: サブスクリプションの数量');
            $table->timestamp('trial_ends_at')->nullable()->comment('試用期間の終了日時');
            $table->timestamp('ends_at')->nullable()->comment('サブスクリプション終了日時');
            $table->timestamps();

            $table->index(['user_id', 'stripe_status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
