<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CorporationSetting;
use Illuminate\Support\Facades\Bus;

class CorporationSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CorporationSetting::truncate();

        CorporationSetting::create([
            'corporation_id' => 1,
            'is_auto_apply' => true,
            'auto_apply_amount' => 3000
        ]);


        CorporationSetting::create([
            'corporation_id' => 2,
            'is_auto_apply' => false,
            'auto_apply_amount' => 3000
        ]);
    }
}
