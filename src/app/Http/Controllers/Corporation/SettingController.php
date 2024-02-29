<?php

namespace App\Http\Controllers\Corporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use App\Enums\EntityType;
use Illuminate\Support\Facades\Redirect;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\BusinessOperator\Transfer\UpdateApplicationRequest;
use App\Http\Requests\BusinessOperator\Transfer\UpdateBankAccountRequest;
use Illuminate\Http\JsonResponse;
use App\Services\CorporationSettingService;
use App\Services\BusinessSettingService;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    private CorporationSettingService $corporationSettingService;
    private BusinessSettingService $businessSettingService;

    public function __construct(CorporationSettingService $corporationSettingService, BusinessSettingService $businessSettingService)
    {
        $this->corporationSettingService = $corporationSettingService;
        $this->businessSettingService = $businessSettingService;
    }

    /**
     * 各種設定選択画面表示
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Corporation/Setting/Index');
    }

    /**
     * 公開設定画面表示
     * @return Response
     */
    public function publish(): Response
    {
        $args = $this->corporationSettingService->publish(Auth::guard('corporation')->user()->corporation_id);

        return Inertia::render('Corporation/Setting/Publish', [
            'list'  => $args
        ]);
    }

    /**
     * API: 公開設定更新
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePublish(Request $request): JsonResponse
    {
        $this->businessSettingService->updatePublish($request->settingId, $request->isPublish);

        return response()->json(['response' => true]);
    }
}
