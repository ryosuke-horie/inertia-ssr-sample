<?php

declare(strict_types=1);

namespace App\Http\Controllers\BusinessOperator\Staff;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\StaffService;

class StaffSettingController extends Controller
{
    private StaffService $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    /**
     * 各種設定画面表示
     * @param int $staffId
     * @return Response
     */
    public function show(int $staffId): Response
    {
        $args = $this->staffService->getSetting($staffId);
        return Inertia::render('BusinessOperator/Staff/Profile/ShowSetting', $args);
    }
}
