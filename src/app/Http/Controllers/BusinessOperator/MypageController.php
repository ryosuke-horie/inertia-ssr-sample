<?php

namespace App\Http\Controllers\BusinessOperator;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use App\Services\BusinessSettingService;
use App\Enums\EntityType;

class MypageController extends Controller
{
    private NotificationService $notificationService;
    private BusinessSettingService $businessSettingService;

    public function __construct(
        NotificationService $notificationService,
        BusinessSettingService $businessSettingService
    ) {
        $this->notificationService = $notificationService;
        $this->businessSettingService = $businessSettingService;
    }

    /**
     * マイページ初期表示
     * @return Response
     */
    public function index(): Response
    {
        $businessId = Auth::guard('business-operator')->user()->business_id;
        // お知らせ情報取得
        $notificationList = $this->notificationService->list(EntityType::BusinessOperator, $businessId, 3);

        // 公開設定を取得
        $isPublish = $this->businessSettingService->publish($businessId)->is_publish;

        return Inertia::render('BusinessOperator/Mypage/Mypage', [
            'businessName'  => Auth::guard('business-operator')->user()->business_name,
            'notifications' => $notificationList->getList(),
            'isPublish'     => $isPublish
        ]);
    }
}
