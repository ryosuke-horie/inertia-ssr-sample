<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StaffReply;

class StaffRepliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StaffReply::truncate();

        StaffReply::create([
            'tip_id' => 2,
            'message' => '応援してくれて感謝します！'
        ]);
    }
}
