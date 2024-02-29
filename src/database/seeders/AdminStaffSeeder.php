<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminStaff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdminStaff::truncate();

        AdminStaff::create([
            'business_id' => 1,
            'email' => 'admin-staff1@example.com',
            'password' => Hash::make('password123'),
            'name' => '管理者タロー',
            'remember_token' => null,
        ]);

        AdminStaff::create([
            'business_id' => 1,
            'email' => 'admin-staff2@example.com',
            'password' => Hash::make('password123'),
            'name' => '管理者ジロー',
            'remember_token' => null,
        ]);
    }
}
