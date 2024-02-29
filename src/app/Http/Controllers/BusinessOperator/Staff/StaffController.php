<?php

declare(strict_types=1);

namespace App\Http\Controllers\BusinessOperator\Staff;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BusinessOperator\StaffProfileUpdateRequest;
use App\Services\StaffService;
use App\Trais\FlashMessageTrait;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    use FlashMessageTrait;

    private StaffService $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    /**
     * スタッフ管理選択画面表示
     * @return Response
     */
    public function select(): Response
    {
        return Inertia::render('BusinessOperator/Staff/Select');
    }

    /**
     * スタッフ一覧画面表示
     * @return Response
     */
    public function index(): Response
    {
        $businessId = Auth::guard('business-operator')->user()->business_id;
        $staffList = $this->staffService->getStaffByBusinessId($businessId);
        return Inertia::render('BusinessOperator/Staff/Index', [
            'staffList' => $staffList
        ]);
    }

    /**
     * スタッフ詳細画面表示
     * @param int $staffId
     * @return Response|RedirectResponse
     */
    public function show(int $staffId): Response|RedirectResponse
    {
        try {
            $args = DB::transaction(function () use ($staffId) {
                return $this->staffService->getProfile($staffId);
            });
            return Inertia::render('BusinessOperator/Staff/Profile/Show', $args);
        } catch (\Exception $e) {
            return Redirect::route('business-operator.staff.index');
        }
    }

    /**
     * スタッフ詳細更新
     * @param int $staffId
     * @param StaffProfileUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(int $staffId, StaffProfileUpdateRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($staffId, $request) {
                $this->staffService->updateProfile($staffId, $request);
            });
            $this->flashMessageSuccess('プロフィールを更新しました');
            return Redirect::route('business-operator.staff.show', ['staffId' => $staffId]);
        } catch (\Exception $e) {
            $this->flashMessageFailed();
            return Redirect::route('business-operator.staff.show', ['staffId' => $staffId]);
        }
    }
}
