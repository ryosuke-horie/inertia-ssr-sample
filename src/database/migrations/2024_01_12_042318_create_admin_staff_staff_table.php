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
        Schema::create('admin_staff_staff', function (Blueprint $table) {
            $table->comment('管理者スタッフとスタッフの中間テーブル');

            // IDや認証に関連するカラム
            $table->unsignedBigInteger('admin_staff_id')->comment('管理者スタッフID');
            $table->unsignedBigInteger('staff_id')->comment('スタッフID');
            $table->foreign('admin_staff_id')->references('admin_staff_id')->on('admin_staff')->onDelete('restrict');
            $table->foreign('staff_id')->references('staff_id')->on('staff')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_staff_staff');
    }
};
