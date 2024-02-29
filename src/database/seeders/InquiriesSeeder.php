<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inquiry;

class InquiriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Inquiry::truncate();

        // サンプルのお問い合わせデータを生成
        Inquiry::create([
            'entity_type' => 1, // 投げ銭ユーザー
            'entity_id' => 2, // 投げ銭ユーザーID（場合によっては具体的なID）
            'name' => 'サンプルユーザー',
            'email' => 'user@example.com',
            'content' => 'サンプルお問い合わせ内容1',
            'status' => 1, // 未対応
        ]);

        Inquiry::create([
            'entity_type' => 2, // スタッフ
            'entity_id' => 2, // スタッフID（場合によっては具体的なID）
            'name' => 'サンプルスタッフ',
            'email' => 'staff@example.com',
            'content' => '別のサンプルお問い合わせ内容2',
            'status' => 2 // 対応中
        ]);

        Inquiry::create([
            'entity_type' => 3, // 事業者
            'entity_id' => 2, // 事業者ID（場合によっては具体的なID）
            'name' => 'サンプル事業者',
            'email' => 'staff@example.com',
            'content' => '別のサンプルお問い合わせ内容3',
            'status' => 3 // 対応済み
        ]);

        Inquiry::create([
            'entity_type' => 4, // 法人
            'entity_id' => 1, // 法人ID（場合によっては具体的なID）
            'name' => 'サンプル法人',
            'email' => 'staff@example.com',
            'content' => '別のサンプルお問い合わせ内容4',
            'status' => 3 // 対応済み
        ]);

        Inquiry::create([
            'entity_type' => 5, // 全体
            'entity_id' => null, // 全体の場合はnull
            'name' => 'サンプル全体',
            'email' => 'free@example.com',
            'content' => '別のサンプルお問い合わせ内容5',
            'status' => 3 // 対応済み
        ]);
    }
}
