<?php

namespace App\Http\Controllers\BusinessOperator;

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
use App\Services\BusinessSettingService;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    private BusinessSettingService $businessSettingService;

    public function __construct(BusinessSettingService $businessSettingService)
    {
        $this->businessSettingService = $businessSettingService;
    }

    /**
     * 各種設定選択画面表示
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('BusinessOperator/Setting/Index');
    }

    /**
     * 公開設定画面表示
     * @return Response
     */
    public function publish(): Response
    {
        $setting = $this->businessSettingService->publish(Auth::guard('business-operator')->user()->business_id);

        return Inertia::render('BusinessOperator/Setting/Publish', [
            'settingId' => $setting->setting_id,
            'isPublish' => $setting->is_publish
        ]);
    }

    /**
     * 公開設定更新
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePublish(Request $request): JsonResponse
    {
        $this->businessSettingService->updatePublish($request->settingId, $request->isPublish);

        return response()->json(['response' => true]);
    }

    /**
     * 口コミ公開設定画面表示
     * @return Response
     */
    public function reviewPublish(): Response
    {
        $setting = $this->businessSettingService->publish(Auth::guard('business-operator')->user()->business_id);

        return Inertia::render('BusinessOperator/Setting/ReviewPublish', [
            'settingId' => $setting->setting_id,
            'isReviewPublish' => $setting->is_review_publish
        ]);
    }

    /**
     * 口コミ公開設定更新
     * @param Request $request
     * @return JsonResponse
     */
    public function updateReviewPublish(Request $request): JsonResponse
    {
        $this->businessSettingService->updateReviewPublish($request->settingId, $request->isReviewPublish);

        return response()->json(['response' => true]);
    }

    /**
     * スタッフランキング公開設定画面表示
     * @return Response
     */
    public function staffRankingPublish(): Response
    {
        $setting = $this->businessSettingService->publish(Auth::guard('business-operator')->user()->business_id);

        return Inertia::render('BusinessOperator/Setting/StaffRankingPublish', [
            'settingId' => $setting->setting_id,
            'isStaffRankingPublish' => $setting->is_staff_ranking_publish
        ]);
    }

    /**
     * スタッフランキング公開設定更新
     * @param Request $request
     * @return JsonResponse
     */
    public function updateStaffRankingPublish(Request $request): JsonResponse
    {
        $this->businessSettingService->updateStaffRankingPublish($request->settingId, $request->isStaffRankingPublish);

        return response()->json(['response' => true]);
    }

    /**
     * 投げ銭金額設定画面表示
     * @return Response
     */
    public function tip(): Response
    {
        $args = $this->businessSettingService->settingTip(Auth::guard('business-operator')->user()->business_id);

        return Inertia::render('BusinessOperator/Setting/Tip', [
            'businessTippingAmountSettings' =>   $args
        ]);
    }

    /**
     * 投げ銭金額設定更新
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateTip(Request $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $this->businessSettingService->updateTip(Auth::guard('business-operator')->user()->business_id, $request->settingIdList);
            });
        } catch (\Exception $e) {
            return Redirect::route('business-operator.setting.tip')->with('success', '予期せぬエラーが発生しました。');
        }
        return Redirect::route('business-operator.setting.tip')->with('success', '更新しました');
    }
}
