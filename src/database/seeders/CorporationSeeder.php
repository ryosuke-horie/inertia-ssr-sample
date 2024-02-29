<?php

namespace Database\Seeders;

use App\Models\Corporation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CorporationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Corporation::truncate();

        Corporation::create([
            'email' => 'test1@example.com',
            'password' => Hash::make('password123'), // パスワードは暗号化する
            'corporation_application_id' => 1, // CorporationApplication ID
            'corporation_name' => 'テスト法人1',
            'zip_code' => '1234567',
            'pref_code' => '13',
            'city' => '東京都渋谷区',
            'phone' => '0312345678',
            'invoice' => '1234567891234',
        ]);

        Corporation::create([
            'email' => 'test2@example.com',
            'password' =>  Hash::make('password123'), // パスワードは暗号化する
            'corporation_application_id' => 2, // CorporationApplication ID
            'corporation_name' => 'テスト法人2',
            'zip_code' => '7654321',
            'pref_code' => '27',
            'city' => '大阪府大阪市',
            'phone' => '0667890123',
            'invoice' => '1234567891234',
        ]);
    }
}
