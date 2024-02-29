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
        Schema::create('subscription_items', function (Blueprint $table) {
            $table->comment('サブスクリプション項目: 個々のサブスクリプションに紐づく項目情報を管理');

            $table->bigIncrements('id')->comment('サブスクリプション項目ID');
            $table->unsignedBigInteger('subscription_id')->comment('サブスクリプションID: サブスクリプションテーブルの外部キー');
            $table->string('stripe_id')->unique()->comment('Stripeの項目ID: Stripeとの統合に使用');
            $table->string('stripe_product')->comment('Stripeの製品ID');
            $table->string('stripe_price')->comment('Stripeの価格ID: 製品の価格');
            $table->integer('quantity')->nullable()->comment('購入数量: 項目の数量');
            $table->timestamps();

            $table->unique(['subscription_id', 'stripe_price']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_items');
    }
};
