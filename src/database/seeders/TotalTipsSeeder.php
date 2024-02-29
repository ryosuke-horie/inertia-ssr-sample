<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TotalTip;
use Carbon\Carbon;

class TotalTipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TotalTip::truncate();

        // サンプルの累計投げ銭データを生成
        $currentYearMonth = Carbon::now()->format('Y-m');

        // ユーザーの累計投げ銭データ
        TotalTip::create([
            'year_month' => $currentYearMonth,
            'entity_type' => 1, // 投げ銭ユーザー
            'entity_id' => 2, // 例: ユーザーID
            'total_amount' => 1000 // 投げ銭総額
        ]);

        TotalTip::create([
            'year_month' => $currentYearMonth,
            'entity_type' => 2, // スタッフ
            'entity_id' => 2, // 例: スタッフID
            'total_amount' => 3000 // 投げ銭総額
        ]);
    }
}
