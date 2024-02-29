<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminStaff;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminStaffStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // AdminStaff::truncate();

        $adminStaff = AdminStaff::find(1);
        $staff = Staff::find([1,2,3]);

        $adminStaff->staffs()->attach($staff);
    }
}
