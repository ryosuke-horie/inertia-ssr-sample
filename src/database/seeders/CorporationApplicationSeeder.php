<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CorporationApplication;

class CorporationApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        CorporationApplication::truncate();

        CorporationApplication::create([
            'email' => 'test1@example.com',
            'corporation_name' => 'テスト法人1',
            'applicant_name' => '田中一郎',
            'applicant_name_kana' => 'タナカイチロウ',
            'zip_code' => '1234567',
            'pref_code' => '13',
            'city' => '東京都渋谷区',
            'phone' => '0312345678',
            'invoice' => '1234567891234',
            'application_status' => 2
        ]);

        CorporationApplication::create([
            'email' => 'test2@example.com',
            'corporation_name' => 'テスト法人2',
            'applicant_name' => '佐藤二郎',
            'applicant_name_kana' => 'サトウジロウ',
            'zip_code' => '7654321',
            'pref_code' => '27',
            'city' => '大阪府大阪市',
            'phone' => '0667890123',
            'invoice' => '1234567891234',
            'application_status' => 2
        ]);

        CorporationApplication::create([
            'email' => 'test3@example.com',
            'corporation_name' => 'テスト法人3',
            'applicant_name' => '鈴木三郎',
            'applicant_name_kana' => 'スズキサブロウ',
            'zip_code' => '1234567',
            'pref_code' => '14',
            'city' => '神奈川県横浜市',
            'phone' => '0456789012',
            'invoice' => '9876543210987',
            'application_status' => 1
        ]);

        CorporationApplication::create([
            'email' => 'test4@example.com',
            'corporation_name' => 'テスト法人4',
            'applicant_name' => '伊藤四郎',
            'applicant_name_kana' => 'イトウシロウ',
            'zip_code' => '2345678',
            'pref_code' => '26',
            'city' => '京都府京都市',
            'phone' => '0751234567',
            'invoice' => '1234567890987',
            'application_status' => 3
        ]);
    }
}
