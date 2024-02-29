<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 既存のデータを削除
        User::truncate();

        User::create([
            'email' => 'guest@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => Carbon::now(),
            'nickname' => 'ゲストユーザー',
            'birthdate' => '1990-01-01',
        ]);

        User::create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => Carbon::now(),
            'nickname' => 'テストユーザー',
            'birthdate' => '1990-01-01',
            'paid_points' => 1500,
            'free_points' => 0,
            'ai_count' => 3,
            'stripe_id' => 'cus_PQWMvmskrBVpmr',
        ]);

        User::create([
            'email' => 'stripe@example.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => Carbon::now(),
            'nickname' => 'ストライプユーザー',
            'birthdate' => '1990-01-01',
            'paid_points' => 1500,
            'free_points' => 0,
            'ai_count' => 3
        ]);
    }
}
