<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StaffSchedule;
use Carbon\Carbon;

class StaffSchedulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        StaffSchedule::truncate();

        // 現在の月の最初の日と最後の日を取得
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // 2週間ごとの範囲を生成
        while ($startOfMonth->lte($endOfMonth)) {
            $endOfWeek = $startOfMonth->copy()->addDays(13);
            if ($endOfWeek->gt($endOfMonth)) {
                $endOfWeek = $endOfMonth->copy();
            }

            // 指定された範囲内の日付データを生成
            $date = $startOfMonth->copy();
            while ($date->lte($endOfWeek)) {
                // スタッフIDが1のデータを生成
                StaffSchedule::create([
                    'staff_id' => 1,
                    'schedule_date' => $date->toDateString(),
                    'shift_status' => rand(1, 3) // 1~3のランダムな値
                ]);

                // スタッフIDが2のデータを生成
                StaffSchedule::create([
                    'staff_id' => 2,
                    'schedule_date' => $date->toDateString(),
                    'shift_status' => rand(1, 3) // 1~3のランダムな値
                ]);

                // スタッフIDが3のデータを生成
                StaffSchedule::create([
                    'staff_id' => 3,
                    'schedule_date' => $date->toDateString(),
                    'shift_status' => rand(1, 3) // 1~3のランダムな値
                ]);

                // スタッフIDが4のデータを生成
                StaffSchedule::create([
                    'staff_id' => 4,
                    'schedule_date' => $date->toDateString(),
                    'shift_status' => rand(1, 3) // 1~3のランダムな値
                ]);

                $date->addDay();
            }

            $startOfMonth = $endOfWeek->addDay();
        }
    }
}
