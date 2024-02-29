<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NotificationRead;

class NotificationReadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 既存のデータを削除
        NotificationRead::truncate();

        for ($i = 1; $i <= 10; $i++) {
            NotificationRead::factory()->create([
                'notification_id' => $i, // 1から10まで順番に設定
            ]);
        }
    }
}
