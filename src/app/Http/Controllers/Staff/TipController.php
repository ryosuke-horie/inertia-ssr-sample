<?php

declare(strict_types=1);

namespace App\Http\Controllers\Staff;

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
use Illuminate\Support\Facades\Session;

class TipController extends Controller
{
    use FlashMessageTrait;

    private TipService $tipService;

    public function __construct(TipService $tipService)
    {
        $this->tipService = $tipService;
    }

    /**
     * 応援履歴一覧画面表示
     * @return Response
     */
    public function index(): Response
    {
        $staffId = Auth::guard('staff')->user()->staff_id;
        $args = $this->tipService->listForStaff($staffId);
        return Inertia::render('Staff/Tip/Index', $args);
    }

    /**
     * 応援履歴詳細画面表示
     * @param int $tipId
     * @return Response|RedirectResponse
     */
    public function show(int $tipId): Response|RedirectResponse
    {
        try {
            $args = DB::transaction(function () use ($tipId) {
                return $this->tipService->show($tipId);
            });
            return Inertia::render('Staff/Tip/Show', $args);
        } catch (\Exception $e) {
            return Redirect::route('staff.tips.index');
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
            return Redirect::route('staff.tips.show', ['tipId' => $tipId]);
        } catch (\Exception $e) {
            return Redirect::route('staff.tips.index');
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
            return Redirect::route('staff.tips.show', ['tipId' => $tipId]);
        } catch (\Exception $e) {
            return Redirect::route('staff.tips.index');
        }
    }
}
