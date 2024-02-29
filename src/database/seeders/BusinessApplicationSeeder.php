<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BusinessApplication;

class BusinessApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        BusinessApplication::truncate();

        // 法人に紐づいた事業者のデータ
        BusinessApplication::create([
            'email' => 'business1@example.com',
            'corporation_id' => 1, // CorporationApplication ID
            'corporation_name' => null,
            'applicant_name' => null,
            'applicant_name_kana' => null,
            'business_name' => 'テスト事業者1',
            'zip_code' => '1234567',
            'pref_code' => '13',
            'city' => '東京都渋谷区',
            'phone' => '0312345678',
            'invoice' => null,
            'business_form' => '株式会社',
            'notes' => '',
            'application_status' => 2
        ]);

        // 事業者単体のデータ
        BusinessApplication::create([
            'email' => 'business2@example.com',
            'corporation_id' => null, // 紐づいていない
            'corporation_name' => '単体事業者法人１',
            'applicant_name' => '単体事業者太郎',
            'applicant_name_kana' => 'タンタイジギョウシャタロウ',
            'business_name' => 'テスト事業者2',
            'zip_code' => '7654321',
            'pref_code' => '27',
            'city' => '大阪府大阪市',
            'phone' => '0667890123',
            'invoice' => '1234567891234',
            'business_form' => '個人事業',
            'notes' => '',
            'application_status' => 2
        ]);

        // 事業者単体のデータ（application_statusが2の事業者）
        BusinessApplication::create([
            'email' => 'business3@example.com',
            'corporation_id' => null, // 紐づいていない
            'corporation_name' => '単体事業者法人２',
            'applicant_name' => '単体事業者次郎',
            'applicant_name_kana' => 'タンタイジギョウシャジロウ',
            'business_name' => 'テスト事業者３',
            'zip_code' => '7654321',
            'pref_code' => '27',
            'city' => '大阪府大阪市',
            'phone' => '0667890123',
            'invoice' => '1234567891234',
            'business_form' => '個人事業',
            'notes' => '',
            'application_status' => 1
        ]);
    }
}
