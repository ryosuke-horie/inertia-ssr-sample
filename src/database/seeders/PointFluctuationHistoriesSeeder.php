<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PointFluctuationHistory;

class PointFluctuationHistoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PointFluctuationHistory::truncate();

        PointFluctuationHistory::create([
            'entity_type' => 1, // 投げ銭ユーザー
            'entity_id' => 1, // 投げ銭ユーザーID
            'point_change' => 1000, // ポイント変動量（ユーザーは購入はプラス）
            'transaction_type' => 2 // トランザクションタイプ (購入)
        ]);

        PointFluctuationHistory::create([
            'entity_type' => 1, // 投げ銭ユーザー
            'entity_id' => 2, // 投げ銭ユーザーID
            'point_change' => 500, // ポイント変動量（ユーザーは購入はプラス）
            'transaction_type' => 2 // トランザクションタイプ (購入)
        ]);

        PointFluctuationHistory::create([
            'entity_type' => 1, // 投げ銭ユーザー
            'entity_id' => 2, // 投げ銭ユーザーID
            'point_change' => 1000, // ポイント変動量（ユーザーは購入はプラス）
            'transaction_type' => 2 // トランザクションタイプ (購入)
        ]);

        PointFluctuationHistory::create([
            'entity_type' => 1, // 投げ銭ユーザー
            'entity_id' => 2, // 投げ銭ユーザーID
            'point_change' => 2000, // ポイント変動量（ユーザーは購入はプラス）
            'transaction_type' => 2 // トランザクションタイプ (購入)
        ]);

        PointFluctuationHistory::create([
            'entity_type' => 1, // 投げ銭ユーザー
            'entity_id' => 1, // 投げ銭ユーザーID
            'point_change' => 1000, // ポイント変動量（ユーザーは投げ銭はマイナス）
            'transaction_type' => 1 // トランザクションタイプ (投げ銭)
        ]);

        PointFluctuationHistory::create([
            'entity_type' => 2, // スタッフ
            'entity_id' => 1, // スタッフID
            'point_change' => 1000, // ポイント変動量（スタッフは投げ銭はプラス）
            'transaction_type' => 1 // トランザクションタイプ (投げ銭)
        ]);

        PointFluctuationHistory::create([
            'entity_type' => 1, // 投げ銭ユーザー
            'entity_id' => 2, // 投げ銭ユーザーID
            'point_change' => 2000, // ポイント変動量（ユーザーは投げ銭はマイナス）
            'transaction_type' => 1 // トランザクションタイプ (投げ銭)
        ]);

        PointFluctuationHistory::create([
            'entity_type' => 2, // スタッフ
            'entity_id' => 2, // スタッフID
            'point_change' => 2000, // ポイント変動量（スタッフは投げ銭はプラス）
            'transaction_type' => 1 // トランザクションタイプ (投げ銭)
        ]);
    }
}
