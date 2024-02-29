<?php

declare(strict_types=1);

namespace App\Http\Controllers\BusinessOperator\AdminStaff;

use App\Http\Controllers\Controller;
use App\Services\AdminStaffService;

class ApiAdminStaffController extends Controller
{
    private AdminStaffService $adminStaffService;

    public function __construct(AdminStaffService $adminStaffService)
    {
        $this->adminStaffService = $adminStaffService;
    }

    /**
     * API:管理者スタッフ削除
     * @param int $adminStaffId
     * @return void
     */
    public function delete(int $adminStaffId): void
    {
        $this->adminStaffService->delete($adminStaffId);
    }
}
