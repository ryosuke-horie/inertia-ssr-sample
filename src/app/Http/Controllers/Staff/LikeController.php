<?php

declare(strict_types=1);

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Services\LikeService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class LikeController extends Controller
{
    private LikeService $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    /**
     * いいね履歴一覧画面表示
     * @return Response
     */
    public function index(): Response
    {
        $staffId = Auth::guard('staff')->user()->staff_id;
        $args = $this->likeService->list($staffId);
        return Inertia::render('Staff/Like/Index', $args);
    }
}
