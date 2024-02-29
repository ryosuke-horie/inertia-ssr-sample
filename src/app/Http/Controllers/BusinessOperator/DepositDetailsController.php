<?php

namespace App\Http\Controllers\BusinessOperator;

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
        $transferRequests = $this->depositDetailsService->getDepositDetails(EntityType::BusinessOperator, Auth::guard('business-operator')->user()->business_id);

        return Inertia::render('BusinessOperator/DepositDetails/Index', ['list' => $transferRequests]);
    }

    /**
     * スタッフ一覧表示
     * @param int $requestId
     * @return Response
     */
    public function staff(int $requestId): Response
    {
        $args = $this->depositDetailsService->getDepositDetailsStaff($requestId);

        return Inertia::render('BusinessOperator/DepositDetails/Staff', $args);
    }
}
