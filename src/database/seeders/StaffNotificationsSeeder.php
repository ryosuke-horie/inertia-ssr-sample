<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StaffNotification;

class StaffNotificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        StaffNotification::truncate();

        // 既存のスタッフIDに対して通知設定データを生成
        $staffIds = [1, 2, 3, 4]; // 例として4つのスタッフIDを使用

        foreach ($staffIds as $staffId) {
            StaffNotification::create([
                'staff_id' => $staffId,
                'is_message_notified' => rand(0, 1) == 1, // ランダムに通知設定を決定
                'token' => null // トークンはnullで初期化
            ]);
        }
    }
}
