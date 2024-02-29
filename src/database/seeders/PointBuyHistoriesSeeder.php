<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PointBuyHistory;
use Carbon\Carbon;

class PointBuyHistoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 既存のデータを削除
        PointBuyHistory::truncate();

        PointBuyHistory::create([
            'user_id' => 1, // ゲストユーザー
            'buy_points' => 1000,
            'amount' => 1000.00,
            'remaining_points' => 0,
            'payment_intent_id' => '123456789',
            'is_paid' => true,
            'expiration_date' => Carbon::now()->addYear(),
        ]);

        PointBuyHistory::create([
            'user_id' => 2,
            'buy_points' => 500,
            'amount' => 500.00,
            'remaining_points' => 0,
            'payment_intent_id' => '987654321',
            'is_paid' => true,
            'expiration_date' => Carbon::now()->addYear(),
        ]);

        PointBuyHistory::create([
            'user_id' => 2,
            'buy_points' => 1000,
            'amount' => 1000.00,
            'remaining_points' => 0,
            'payment_intent_id' => '987654321',
            'is_paid' => true,
            'expiration_date' => Carbon::now()->addYear(),
        ]);

        PointBuyHistory::create([
            'user_id' => 2,
            'buy_points' => 2000,
            'amount' => 2000.00,
            'remaining_points' => 1500,
            'payment_intent_id' => '987654321',
            'is_paid' => true,
            'expiration_date' => Carbon::now()->addYear(),
        ]);
    }
}
