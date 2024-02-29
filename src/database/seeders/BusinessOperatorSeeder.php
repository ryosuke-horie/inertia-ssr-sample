<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BusinessOperator;
use Illuminate\Support\Facades\Hash;

class BusinessOperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        BusinessOperator::truncate();

        // 法人に紐づいた事業者のデータ
        BusinessOperator::create([
            'email' => 'business1@example.com',
            'password' => Hash::make('password123'),
            'corporation_id' => 1,
            'business_application_id' => 1,
            'business_name' => 'テスト事業者1',
            'zip_code' => '1234567',
            'pref_code' => '13',
            'city' => '東京都渋谷区',
            'phone' => '0312345678',
            'business_form' => '飲食店',
            'business_status' => 2,
            'lead_company' => 1,
        ]);

        // 事業者単体のデータ
        BusinessOperator::create([
            'email' => 'business2@example.com',
            'password' => Hash::make('password123'),
            'corporation_id' => null,
            'business_application_id' => 2,
            'corporation_name' => '単体事業者法人１',
            'business_name' => 'テスト事業者2',
            'zip_code' => '7654321',
            'pref_code' => '27',
            'city' => '大阪府大阪市',
            'phone' => '0667890123',
            'business_form' => '飲食店',
            'business_status' => 2,
            'invoice' => '1234567891234',
            'lead_company' => 3,
        ]);
    }
}
