<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserTip;

class UserTipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserTip::truncate();

        UserTip::create([
            'user_id' => 1, // ゲストユーザーID
            'staff_id' => 1, // スタッフID
            'guest_nickname' => 'ゲスト',
            'tipping_amount' => 1000,
            'message' => 'がんばってください！',
            'desk_number' => 1,
            'is_user_read' => false,
            'is_staff_read' => false
        ]);

        UserTip::create([
            'user_id' => 2,
            'staff_id' => 2, // 別のスタッフID
            'guest_nickname' => null,
            'tipping_amount' => 2000,
            'message' => '素晴らしいパフォーマンスでした！',
            'desk_number' => 2,
            'is_user_read' => false,
            'is_staff_read' => false
        ]);
    }
}
