<?php

declare(strict_types=1);

namespace App\Http\Controllers\BusinessOperator\AdminStaff;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BusinessOperator\AdminStaffCreateRequest;
use App\Services\AdminStaffService;
use App\Services\StaffService;
use App\Trais\FlashMessageTrait;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class AdminStaffRegisterdController extends Controller
{
    use FlashMessageTrait;

    private AdminStaffService $adminStaffService;
    private StaffService $staffService;

    public function __construct(AdminStaffService $adminStaffService, StaffService $staffService)
    {
        $this->adminStaffService = $adminStaffService;
        $this->staffService = $staffService;
    }

    /**
     * 管理者スタッフトークン登録表示
     * @return Response
     */
    public function show(): Response
    {
        $businessId = Auth::guard('business-operator')->user()->business_id;
        $staffList = $this->staffService->getStaffByBusinessId($businessId);
        return Inertia::render('BusinessOperator/Staff/Admin/Create/Show', [
            'staffList' => $staffList
        ]);
    }

    /**
     * 管理者スタッフトークン登録
     * @param AdminStaffCreateRequest $request
     * @return RedirectResponse
     */
    public function create(AdminStaffCreateRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $businessId = Auth::guard('business-operator')->user()->business_id;
                $name = $request->input('name');
                $email = $request->input('email');
                $staffIds = $request->input('staffIds');
                $this->adminStaffService->registerToken($businessId, $name, $email, $staffIds);
            });
            $this->flashMessageSuccess('登録用メールを送信しました。');
            return Redirect::route('business-operator.staff.admin.index');
        } catch (\Exception $e) {
            $this->flashMessageFailed();
            return Redirect::route('business-operator.staff.admin.add.show');
        }
    }
}
