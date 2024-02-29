<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\UserPointHistoryService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PointHistoryController extends Controller
{
    private UserPointHistoryService $userPointHistoryService;

    public function __construct(UserPointHistoryService $userPointHistoryService)
    {
        $this->userPointHistoryService = $userPointHistoryService;
    }

    public function index(): Response
    {
        $userId = Auth::guard('user')->user()->user_id;
        $args = $this->userPointHistoryService->getPoint($userId);
        return Inertia::render('User/PointHistory/Index', $args);
    }

    public function pointIndex(): Response
    {
        $userId = Auth::guard('user')->user()->user_id;
        $args = $this->userPointHistoryService->getPointHistoryList($userId);
        return Inertia::render('User/PointHistory/Point/Index', $args);
    }

    public function expiredIndex(): Response
    {
        $userId = Auth::guard('user')->user()->user_id;
        $args = $this->userPointHistoryService->getPointExpiredList($userId);
        return Inertia::render('User/PointHistory/Expired/Index', $args);
    }
}
