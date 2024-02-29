<?php

declare(strict_types=1);

namespace App\Http\Controllers\AdminStaff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\TipCreateReplyRequest;
use App\Services\TipService;
use App\Trais\FlashMessageTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class TipController extends Controller
{
    use FlashMessageTrait;

    private TipService $tipService;

    public function __construct(TipService $tipService)
    {
        $this->tipService = $tipService;
    }

    /**
     * スタッフ応援履歴一覧画面表示
     * @return Response
     */
    public function index(): Response
    {
        $adminStaffId = Auth::guard('admin-staff')->user()->admin_staff_id;
        $args = $this->tipService->listForAdminStaff($adminStaffId);
        return Inertia::render('AdminStaff/Tip/Index', $args);
    }

    /**
     * スタッフ応援履歴詳細画面表示
     * @param int $tipId
     * @return Response|RedirectResponse
     */
    public function show(int $tipId): Response|RedirectResponse
    {
        try {
            $args = DB::transaction(function () use ($tipId) {
                return $this->tipService->show($tipId);
            });
            return Inertia::render('AdminStaff/Tip/Show', $args);
        } catch (\Exception $e) {
            return Redirect::route('admin-staff.tips.index');
        }
    }

    /**
     * 応援履歴スタッフ返信作成
     * @param int $tipId
     * @param TipCreateReplyRequest $request
     * @return RedirectResponse
     */
    public function create(int $tipId, TipCreateReplyRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($tipId, $request) {
                $this->tipService->createStaffReply($tipId, $request);
            });
            $this->flashMessageSuccess('メッセージを返信しました');
            return Redirect::route('admin-staff.tips.show', ['tipId' => $tipId]);
        } catch (\Exception $e) {
            $this->flashMessageFailed();
            return Redirect::route('admin-staff.tips.index');
        }
    }

    /**
     * 応援履歴スタッフ返信削除
     * @param int $tipId
     * @param int $replyId
     * @return RedirectResponse
     */
    public function delete(int $tipId, int $replyId): RedirectResponse
    {
        try {
            DB::transaction(function () use ($replyId) {
                $this->tipService->deleteStaffReply($replyId);
            });
            $this->flashMessageSuccess('メッセージを削除しました');
            return Redirect::route('admin-staff.tips.show', ['tipId' => $tipId]);
        } catch (\Exception $e) {
            $this->flashMessageFailed();
            return Redirect::route('admin-staff.tips.index');
        }
    }
}
