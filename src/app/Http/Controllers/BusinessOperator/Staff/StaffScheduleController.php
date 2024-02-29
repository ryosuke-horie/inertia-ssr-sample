<?php

declare(strict_types=1);

namespace App\Http\Controllers\BusinessOperator\Staff;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\StaffService;

class StaffScheduleController extends Controller
{
    private StaffService $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    /**
     * スケジュール画面表示
     * @return Response
     */
    public function index(): Response
    {
        $businessId = Auth::guard('business-operator')->user()->business_id;
        $staffList = $this->staffService->getStaffByBusinessId($businessId);
        return Inertia::render('BusinessOperator/Staff/Schedule/Show', [
            'staffList' => $staffList
        ]);
    }
}
