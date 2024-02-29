<?php

declare(strict_types=1);

namespace App\Http\Controllers\BusinessOperator\Staff;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Staff\TipCreateReplyRequest;
use App\Services\TipService;
use App\Trais\FlashMessageTrait;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class StaffTipController extends Controller
{
    use FlashMessageTrait;

    private TipService $tipService;

    public function __construct(TipService $tipService)
    {
        $this->tipService = $tipService;
    }

    /**
     * 投げ銭一覧画面表示
     * @return Response
     */
    public function index(): Response
    {
        $businessId = Auth::guard('business-operator')->user()->business_id;
        $args = $this->tipService->listForBusiness($businessId);
        return Inertia::render('BusinessOperator/Staff/Tip/Index', $args);
    }

    /**
     * 投げ銭詳細画面表示
     * @param int $tipId
     * @return Response
     */
    public function show(int $tipId): Response
    {
        $args = $this->tipService->show($tipId);
        return Inertia::render('BusinessOperator/Staff/Tip/Show', $args);
    }

    /**
     * 投げ銭返信作成
     * @param int $tipId
     * @param TipCreateReplyRequest $request
     * @return RedirectResponse
     */
    public function createTipReply(int $tipId, TipCreateReplyRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($tipId, $request) {
                $this->tipService->createStaffReply($tipId, $request);
            });
            $this->flashMessageSuccess('メッセージを返信しました');
            return Redirect::route('business-operator.staff.tips.show', ['tipId' => $tipId]);
        } catch (\Exception $e) {
            $this->flashMessageFailed();
            return Redirect::route('business-operator.staff.tips.index');
        }
    }

    /**
     * 投げ銭返信削除
     * @param int $tipId
     * @param int $replyId
     * @return RedirectResponse
     */
    public function deleteTipReply(int $tipId, int $replyId): RedirectResponse
    {
        try {
            DB::transaction(function () use ($replyId) {
                $this->tipService->deleteStaffReply($replyId);
            });
            $this->flashMessageSuccess('メッセージを削除しました');
            return Redirect::route('business-operator.staff.tips.show', ['tipId' => $tipId]);
        } catch (\Exception $e) {
            $this->flashMessageFailed();
            return Redirect::route('business-operator.staff.tips.index');
        }
    }
}
