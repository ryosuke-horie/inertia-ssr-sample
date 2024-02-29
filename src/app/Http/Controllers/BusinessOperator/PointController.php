<?php

namespace App\Http\Controllers\BusinessOperator;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\TotalPointService;

class PointController extends Controller
{
    private TotalPointService $totalPointService;

    public function __construct(TotalPointService $totalPointService)
    {
        $this->totalPointService = $totalPointService;
    }

    /**
     * 一覧表示
     * @return Response
     */
    public function index(): Response
    {
        $args = $this->totalPointService->businessOperatorTotalPoint(Auth::guard('business-operator')->user()->business_id);

        return Inertia::render('BusinessOperator/Point/Index', [
            'businessId'    => Auth::guard('business-operator')->user()->business_id,
            'totalPoint'    => $args['totalPoint'],
            'yearMonthList' => $args['yearMonthList']
        ]);
    }

    /**
     * スタッフ一覧表示
     * @return Response
     */
    public function staff(string $yearMonth): Response
    {
        $newYearMonth = str_replace(['年', '月'], ['-', ''], $yearMonth);

        $args = $this->totalPointService->businessOperatorStaffTotalPoint(Auth::guard('business-operator')->user()->business_id, $newYearMonth);
        return Inertia::render('BusinessOperator/Point/Staff', [
            'yearMonth'     => $yearMonth,
            'isExchange'    => $args['isExchange'],
            'totalPoint'    => $args['totalPoint'],
            'staffList'     => $args['staffList']
        ]);
    }
}
