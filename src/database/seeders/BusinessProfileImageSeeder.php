<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BusinessProfileImage;
use Carbon\Carbon;

class BusinessProfileImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 既存のデータを削除
        BusinessProfileImage::truncate();

        BusinessProfileImage::create([
            'business_id' => 1, // 既存のユーザーIDを想定
            'file_type' => 'jpeg',
            'file_name' => 'business1',
            'file_size' => 2048,
            'order' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        BusinessProfileImage::create([
            'business_id' => 2, // 既存のユーザーIDを想定
            'file_type' => 'png',
            'file_name' => 'business2',
            'file_size' => 1024,
            'order' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        BusinessProfileImage::create([
            'business_id' => 2, // 既存のユーザーIDを想定
            'file_type' => 'png',
            'file_name' => 'business2_2.png',
            'file_size' => 1024,
            'order' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
