<?php

declare(strict_types=1);

namespace App\Http\Controllers\Staff;

use App\Enums\EntityType;
use App\Http\Controllers\Controller;
use App\Services\PointHistoryService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PointHistoryController extends Controller
{
    private PointHistoryService $pointHistoryService;

    public function __construct(PointHistoryService $pointHistoryService)
    {
        $this->pointHistoryService = $pointHistoryService;
    }

    /**
     * ポイント履歴一覧画面表示
     * @return Response
     */
    public function index(): Response
    {
        $staffId = Auth::guard('staff')->user()->staff_id;
        $args = $this->pointHistoryService->list(EntityType::Staff, $staffId);
        return Inertia::render('Staff/PointHistory/Index', $args);
    }
}
