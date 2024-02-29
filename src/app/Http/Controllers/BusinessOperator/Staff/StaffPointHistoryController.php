<?php

declare(strict_types=1);

namespace App\Http\Controllers\BusinessOperator\Staff;

use App\Enums\EntityType;
use App\Http\Controllers\Controller;
use App\Services\PointHistoryService;
use Inertia\Inertia;
use Inertia\Response;

class StaffPointHistoryController extends Controller
{
    private PointHistoryService $pointHistoryService;

    public function __construct(PointHistoryService $pointHistoryService)
    {
        $this->pointHistoryService = $pointHistoryService;
    }

    /**
     * スタッフポイント変動履歴一覧画面表示
     * @return Response
     */
    public function index(int $staffId): Response
    {
        $args = $this->pointHistoryService->list(EntityType::Staff, $staffId);
        return Inertia::render('BusinessOperator/Staff/Profile/PointHistory/Index', [
            'staffId'        => $staffId,
            'pointHistories' => $args['pointHistories'],
            'points'         => $args['points'],
        ]);
    }
}
