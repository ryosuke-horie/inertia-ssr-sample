<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserProfileImage;
use Carbon\Carbon;

class UserProfileImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 既存のデータを削除
        UserProfileImage::truncate();

        UserProfileImage::create([
            'user_id' => 1, // 既存のユーザーIDを想定
            'file_type' => 'jpeg',
            'file_name' => 'guest',
            'file_size' => 2048,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        UserProfileImage::create([
            'user_id' => 2, // 既存のユーザーIDを想定
            'file_type' => 'png',
            'file_name' => 'user1',
            'file_size' => 1024,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        UserProfileImage::create([
            'user_id' => 3, // 既存のユーザーIDを想定
            'file_type' => 'png',
            'file_name' => 'user1',
            'file_size' => 1024,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
