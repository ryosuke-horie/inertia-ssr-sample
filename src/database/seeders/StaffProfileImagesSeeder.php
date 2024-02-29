<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StaffProfileImage;

class StaffProfileImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StaffProfileImage::truncate();

        StaffProfileImage::create([
            'staff_id' => 1, // スタッフIDを適宜設定
            'file_type' => 'jpg',
            'file_name' => 'staff1',
            'file_size' => 102400, // ファイルサイズ（バイト単位）
            'order' => 1
        ]);

        StaffProfileImage::create([
            'staff_id' => 1, // スタッフIDを適宜設定
            'file_type' => 'jpg',
            'file_name' => 'staff1_2',
            'file_size' => 102400, // ファイルサイズ（バイト単位）
            'order' => 2
        ]);

        StaffProfileImage::create([
            'staff_id' => 2, // スタッフIDを適宜設定
            'file_type' => 'png',
            'file_name' => 'staff2',
            'file_size' => 204800, // ファイルサイズ（バイト単位）
            'order' => 1
        ]);

        StaffProfileImage::create([
            'staff_id' => 2, // スタッフIDを適宜設定
            'file_type' => 'png',
            'file_name' => 'staff2_2',
            'file_size' => 204800, // ファイルサイズ（バイト単位）
            'order' => 2
        ]);

        StaffProfileImage::create([
            'staff_id' => 2, // スタッフIDを適宜設定
            'file_type' => 'png',
            'file_name' => 'staff2_3',
            'file_size' => 204800, // ファイルサイズ（バイト単位）
            'order' => 3
        ]);

        StaffProfileImage::create([
            'staff_id' => 3, // スタッフIDを適宜設定
            'file_type' => 'gif',
            'file_name' => 'staff3',
            'file_size' => 307200, // ファイルサイズ（バイト単位）
            'order' => 1
        ]);

        StaffProfileImage::create([
            'staff_id' => 4, // スタッフIDを適宜設定
            'file_type' => 'jpg',
            'file_name' => 'staff4',
            'file_size' => 409600, // ファイルサイズ（バイト単位）
            'order' => 1
        ]);
    }
}
