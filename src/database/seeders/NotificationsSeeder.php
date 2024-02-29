<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use Illuminate\Support\Carbon;

class NotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // 既存のデータを削除
        Notification::truncate();

        // ファクトリーを使用して複数の通知を生成
        Notification::factory()->count(10)->create();
    }
}
