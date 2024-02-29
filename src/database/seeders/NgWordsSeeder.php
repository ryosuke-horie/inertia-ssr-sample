<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NgWord;

class NgWordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NgWord::truncate();

        // サンプルのNGワードデータを生成
        $words = [
            'めくら', 'つんぼ', '知恵遅れ', '植物人間',
            '糞', '性交', '小便', 'ボケ', 'だまれ', '死ね', '殺す',
            '殺人', '殺してやる', '殺すぞ', '殺人者', '殺人犯', '殺人罪','しきもう',
            '殺人事件', '殺人容疑', '殺人未遂', '殺人鬼', '殺人現場', '殺人予告',
        ];

        foreach ($words as $word) {
            NgWord::create([
                'word' => $word
            ]);
        }
    }
}
