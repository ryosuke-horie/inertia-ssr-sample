<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Bus;

class BusinessSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BusinessSetting::truncate();

        BusinessSetting::create([
            'business_id' => 2,
            'is_publish' => true,
            'is_media_publish' => true,
            'is_review_publish' => true,
            'is_staff_ranking_publish' => false,
            'is_auto_apply' => true,
            'auto_apply_amount' => 2000
        ]);

        BusinessSetting::create([
            'business_id' => 1,
            'is_media_publish' => true,
            'is_review_publish' => true,
            'is_staff_ranking_publish' => false,
            'is_auto_apply' => false,
            'auto_apply_amount' => 2000
        ]);
    }
}
