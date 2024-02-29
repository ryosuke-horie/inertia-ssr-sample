<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::truncate();

        // サンプルの管理者データを生成
        Admin::create([
            'name' => 'admin',
            'password' => Hash::make('password123'), // パスワードは適切にハッシュ化
            'role' => 1 // 基本権限
        ]);
    }
}
