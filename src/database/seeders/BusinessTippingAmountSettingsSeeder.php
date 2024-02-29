<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BusinessTippingAmountSetting;

class BusinessTippingAmountSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BusinessTippingAmountSetting::truncate();

        BusinessTippingAmountSetting::create([
            'business_id' => 1, // 事業者ID
            'setting_id' => 1, // 投げ銭金額設定ID
        ]);
        BusinessTippingAmountSetting::create([
            'business_id' => 1, // 事業者ID
            'setting_id' => 2, // 投げ銭金額設定ID
        ]);
        BusinessTippingAmountSetting::create([
            'business_id' => 1, // 事業者ID
            'setting_id' => 3, // 投げ銭金額設定ID
        ]);
        BusinessTippingAmountSetting::create([
            'business_id' => 1, // 事業者ID
            'setting_id' => 4, // 投げ銭金額設定ID
        ]);
        BusinessTippingAmountSetting::create([
            'business_id' => 1, // 事業者ID
            'setting_id' => 5, // 投げ銭金額設定ID
        ]);

        BusinessTippingAmountSetting::create([
            'business_id' => 2, // 別の事業者ID
            'setting_id' => 1, // 別の投げ銭金額設定ID
        ]);
        BusinessTippingAmountSetting::create([
            'business_id' => 2, // 別の事業者ID
            'setting_id' => 2, // 別の投げ銭金額設定ID
        ]);
        BusinessTippingAmountSetting::create([
            'business_id' => 2, // 別の事業者ID
            'setting_id' => 3, // 別の投げ銭金額設定ID
        ]);
        BusinessTippingAmountSetting::create([
            'business_id' => 2, // 別の事業者ID
            'setting_id' => 4, // 別の投げ銭金額設定ID
        ]);
        BusinessTippingAmountSetting::create([
            'business_id' => 2, // 別の事業者ID
            'setting_id' => 5, // 別の投げ銭金額設定ID
        ]);
    }
}
