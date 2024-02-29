<?php

namespace App\Http\Controllers\Corporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use App\Enums\EntityType;
use App\Services\DepositDetailsService;

class DepositDetailsController extends Controller
{
    private DepositDetailsService $depositDetailsService;

    public function __construct(DepositDetailsService $depositDetailsService)
    {
        $this->depositDetailsService = $depositDetailsService;
    }

    /**
     * 一覧表示
     * @return Response
     */
    public function index(): Response
    {
        $transferRequests = $this->depositDetailsService->getDepositDetails(EntityType::Corporation, Auth::guard('corporation')->user()->corporation_id);

        return Inertia::render('Corporation/DepositDetails/Index', ['list' => $transferRequests]);
    }

    /**
     * 事業者一覧表示
     * @param int $requestId
     * @return Response
     */
    public function business(int $requestId): Response
    {
        $args = $this->depositDetailsService->getCorporationDepositDetailsBusiness($requestId);

        return Inertia::render('Corporation/DepositDetails/Business', $args);
    }

    /**
     * スタッフ一覧表示
     * @param int $requestId
     * @param int $businessId
     * @return Response
     */
    public function staff(int $requestId, int $businessId): Response
    {
        $args = $this->depositDetailsService->getCorporationDepositDetailsStaff($requestId, $businessId);

        return Inertia::render('Corporation/DepositDetails/Staff', $args);
    }
}
