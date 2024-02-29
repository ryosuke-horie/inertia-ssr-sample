<?php

declare(strict_types=1);

namespace App\Http\Controllers\Staff;

use App\Enums\EntityType;
use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use App\Services\StaffService;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;

class MypageController extends Controller
{
    private $staffService;
    private $notificationService;

    public function __construct(
        StaffService $staffService,
        NotificationService $notificationService,
    ) {
        $this->staffService = $staffService;
        $this->notificationService = $notificationService;
    }

    /**
     * スタッフマイページ画面表示
     * @return Response
     */
    public function index(): Response
    {
        $staffId = Auth::guard('staff')->user()->staff_id;
        $staff = $this->staffService->getStaff($staffId);
        $notificationList = $this->notificationService->list(EntityType::Staff, $staffId, 3);
        return Inertia::render('Staff/Mypage/Mypage', [
            'favorites'     => $staff['favorites'],
            'staffName'     => $staff['staffName'],
            'staffImage'    => $staff['staffImage'],
            'businessName'  => $staff['businessName'],
            'notifications' => $notificationList->getList(),
        ]);
    }
}
