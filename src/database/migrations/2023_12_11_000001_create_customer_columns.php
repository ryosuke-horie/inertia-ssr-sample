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
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_id')->nullable()->index()->comment('Stripeの顧客ID: Stripeとの統合に使用');
            $table->string('pm_type')->nullable()->comment('支払い方法のタイプ: 例「card」');
            $table->string('pm_last_four', 4)->nullable()->comment('支払い方法のカード番号の最後の4桁');
            $table->timestamp('trial_ends_at')->nullable()->comment('試用期間の終了日時: 試用サービスの終了時刻を記録');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_id',
                'pm_type',
                'pm_last_four',
                'trial_ends_at',
            ]);
        });
    }
};
