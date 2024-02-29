<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Staff::truncate();

        Staff::create([
            'email' => 'staff1@example.com',
            'password' => Hash::make('password123'),
            'business_id' => 1,
            'real_name' => '山田 太郎',
            'staff_name' => 'タロー',
            'comment' => '頑張ります！',
            'points' => 0,
            'remember_token' => null,
        ]);

        Staff::create([
            'email' => 'staff2@example.com',
            'password' => Hash::make('password123'),
            'business_id' => 2,
            'real_name' => '鈴木 花子',
            'staff_name' => 'ハナコ',
            'comment' => 'よろしくお願いします。',
            'points' => 3000,
            'remember_token' => null,
        ]);

        Staff::create([
            'email' => 'staff3@example.com',
            'password' => Hash::make('password123'),
            'business_id' => 2,
            'real_name' => '佐藤 大樹',
            'staff_name' => 'ダイキ',
            'comment' => 'やる気だけはあります！',
            'points' => 0,
            'remember_token' => null,
        ]);

        Staff::create([
            'email' => 'staff4@example.com',
            'password' => Hash::make('password123'),
            'business_id' => 2,
            'real_name' => '間宮 美咲',
            'staff_name' => 'ミサ',
            'comment' => '投げ銭！',
            'points' => 0,
            'remember_token' => null,
        ]);
    }
}
