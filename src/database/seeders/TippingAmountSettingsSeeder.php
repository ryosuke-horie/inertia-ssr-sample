<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TippingAmountSetting;

class TippingAmountSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TippingAmountSetting::truncate();

        TippingAmountSetting::create([
            'amount' => 300, // 有償投げ銭額
            'free_amount' => 0 // 無償投げ銭額
        ]);

        TippingAmountSetting::create([
            'amount' => 500, // 有償投げ銭額
            'free_amount' => 0 // 無償投げ銭額
        ]);
        TippingAmountSetting::create([
            'amount' => 1000, // 有償投げ銭額
            'free_amount' => 0 // 無償投げ銭額
        ]);

        TippingAmountSetting::create([
            'amount' => 2000, // 有償投げ銭額
            'free_amount' => 0 // 無償投げ銭額
        ]);

        TippingAmountSetting::create([
            'amount' => 3000, // 有償投げ銭額
            'free_amount' => 0 // 無償投げ銭額
        ]);

        TippingAmountSetting::create([
            'amount' => 4000, // 有償投げ銭額
            'free_amount' => 0 // 無償投げ銭額
        ]);

        TippingAmountSetting::create([
            'amount' => 5000, // 有償投げ銭額
            'free_amount' => 500 // 無償投げ銭額
        ]);

        TippingAmountSetting::create([
            'amount' => 10000, // 有償投げ銭額
            'free_amount' => 1000 // 無償投げ銭額
        ]);

        TippingAmountSetting::create([
            'amount' => 20000, // 有償投げ銭額
            'free_amount' => 2000 // 無償投げ銭額
        ]);

        TippingAmountSetting::create([
            'amount' => 30000, // 有償投げ銭額
            'free_amount' => 3000 // 無償投げ銭額
        ]);
    }
}
