<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReplyMedia;

class ReplyMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReplyMedia::truncate();

        ReplyMedia::create([
            'reply_id' => 1, // 既存の返信ID
            'file_type' => 'jpg',
            'file_name' => 'reply1.jpg',
            'file_size' => 1024000, // ファイルサイズ（バイト単位）
        ]);
    }
}
