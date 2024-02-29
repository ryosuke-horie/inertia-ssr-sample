<?php

namespace App\Http\Controllers\Corporation;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\TotalPointService;
use Carbon\Carbon;

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
        $args = $this->totalPointService->corporationTotalPoint(Auth::guard('corporation')->user()->corporation_id);

        return Inertia::render('Corporation/Point/Index', [
            'corporationId'    => Auth::guard('corporation')->user()->corporation_id,
            'totalPoint'    => $args['totalPoint'],
            'yearMonthList' => $args['yearMonthList']
        ]);
    }

    /**
     * 事業者一覧表示
     * @param string $yearMonth
     * @return Response
     */
    public function businessOperator(string $yearMonth): Response
    {
        $args = $this->totalPointService->corporationBusinessOperatorTotalPoint(Auth::guard('corporation')->user()->corporation_id, $yearMonth);

        return Inertia::render('Corporation/Point/BusinessOperator', [
            'yearMonth'         => $yearMonth,
            'yearMonthDisplay'  => Carbon::parse($yearMonth)->format('Y年m月'),
            'isExchange'        => $args['isExchange'],
            'totalPoint'        => $args['totalPoint'],
            'businessOperators' => $args['businessOperators']
        ]);
    }

    /**
     * スタッフ一覧表示
     * @return Response
     */
    public function staff(string $yearMonth, int $businessId): Response
    {
        $args = $this->totalPointService->businessOperatorStaffTotalPoint($businessId, $yearMonth);

        return Inertia::render('Corporation/Point/Staff', [
            'yearMonth'     => Carbon::parse($yearMonth)->format('Y年m月'),
            'isExchange'    => $args['isExchange'],
            'totalPoint'    => $args['totalPoint'],
            'staffList'     => $args['staffList']
        ]);
    }
}
