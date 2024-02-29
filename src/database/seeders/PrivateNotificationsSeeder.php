<?php

namespace Database\Seeders;

use App\Models\PrivateNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PrivateNotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 既存のデータを削除
        DB::table('private_notifications')->truncate();

        // ファクトリーを使用して複数のプライベート通知を生成
        PrivateNotification::factory()->count(50)->create();
    }
}
