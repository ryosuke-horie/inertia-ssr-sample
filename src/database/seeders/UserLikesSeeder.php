<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserLike;

class UserLikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserLike::truncate();

        UserLike::create([
            'user_id' => 2, // テストユーザーID
            'staff_id' => 1, // スタッフID
        ]);

        UserLike::create([
            'user_id' => 2, // テストユーザーID
            'staff_id' => 2, // 別のスタッフID
        ]);

        UserLike::create([
            'user_id' => 2, // テストユーザーID
            'staff_id' => 3, // 別のスタッフID
        ]);
    }
}
