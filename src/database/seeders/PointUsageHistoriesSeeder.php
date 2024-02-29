<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PointUsageHistory;

class PointUsageHistoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PointUsageHistory::truncate();

        PointUsageHistory::create([
            'buy_id' => 1, // ポイント詳細ID
            'tip_id' => 1, // 投げ銭ID
            'used_points' => 1000 // 利用ポイント数
        ]);

        PointUsageHistory::create([
            'buy_id' => 2, // 別のポイント詳細ID
            'tip_id' => 2, // 別の投げ銭ID
            'used_points' => 500 // 利用ポイント数
        ]);

        PointUsageHistory::create([
            'buy_id' => 3, // 別のポイント詳細ID
            'tip_id' => 2, // 別の投げ銭ID
            'used_points' => 1000 // 利用ポイント数
        ]);

        PointUsageHistory::create([
            'buy_id' => 4, // 別のポイント詳細ID
            'tip_id' => 2, // 別の投げ銭ID
            'used_points' => 500 // 利用ポイント数
        ]);
    }
}
