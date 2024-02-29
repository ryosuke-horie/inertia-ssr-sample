<?php

declare(strict_types=1);

namespace App\Http\Controllers\BusinessOperator\AdminStaff;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BusinessOperator\AdminStaffProfileUpdateRequest;
use App\Services\AdminStaffService;
use App\Trais\FlashMessageTrait;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class AdminStaffController extends Controller
{
    use FlashMessageTrait;

    private AdminStaffService $adminStaffService;

    public function __construct(AdminStaffService $adminStaffService)
    {
        $this->adminStaffService = $adminStaffService;
    }

    /**
     * 管理者スタッフ一覧画面表示
     * @return Response
     */
    public function index(): Response
    {
        $businessId = Auth::guard('business-operator')->user()->business_id;
        $adminStaffList = $this->adminStaffService->list($businessId);

        return Inertia::render('BusinessOperator/Staff/Admin/Index', [
            'adminStaffList' => $adminStaffList
        ]);
    }

    /**
     * 管理者スタッフ詳細表示
     * @param int $adminStaffId
     * @return Response
     */
    public function show(int $adminStaffId): Response
    {
        $businessId = Auth::guard('business-operator')->user()->business_id;
        $args = $this->adminStaffService->detail($businessId, $adminStaffId);
        return Inertia::render('BusinessOperator/Staff/Admin/Show', $args);
    }

    /**
     * 管理者スタッフ更新
     * @param int $adminStaffId
     * @param AdminStaffProfileUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(int $adminStaffId, AdminStaffProfileUpdateRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($adminStaffId, $request) {
                $name = $request->name;
                $staffIds = $request->staffIds;
                $businessId = Auth::guard('business-operator')->user()->business_id;
                $this->adminStaffService->update($businessId, $adminStaffId, $name, $staffIds);
            });
            $this->flashMessageSuccess('プロフィールを更新しました');
            return Redirect::route('business-operator.staff.admin.show', ['adminStaffId' => $adminStaffId]);
        } catch (\Exception $e) {
            $this->flashMessageFailed();
            return Redirect::route('business-operator.staff.admin.index');
        }
    }
}
