<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StaffFavorite;

class StaffFavoritesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StaffFavorite::truncate();

        StaffFavorite::create([
            'user_id' => 2, // 投げ銭ユーザーID
            'staff_id' => 1  // スタッフID
        ]);

        StaffFavorite::create([
            'user_id' => 2,
            'staff_id' => 2  // 別のスタッフID
        ]);
    }
}
