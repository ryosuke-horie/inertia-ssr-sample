<?php

namespace App\Http\Controllers\Corporation;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use App\Services\CorporationSettingService;
use App\Enums\EntityType;

class MypageController extends Controller
{
    private NotificationService $notificationService;
    private CorporationSettingService $corporationSettingService;

    public function __construct(
        NotificationService $notificationService,
        CorporationSettingService $corporationSettingService
    ) {
        $this->notificationService = $notificationService;
        $this->corporationSettingService = $corporationSettingService;
    }

    /**
     * マイページ初期表示
     * @return Response
     */
    public function index(): Response
    {
        $corporationId = Auth::guard('corporation')->user()->corporation_id;

        // お知らせ情報取得
        $notificationList = $this->notificationService->list(EntityType::Corporation, $corporationId, 3);

        // 公開設定を取得
        $isPrivatePublish = $this->corporationSettingService->isPublish($corporationId);

        return Inertia::render('Corporation/Mypage/Mypage', [
            'corporationName'  => Auth::guard('corporation')->user()->corporation_name,
            'notifications' => $notificationList->getList(),
            'isPrivatePublish'  => $isPrivatePublish
        ]);
    }
}
