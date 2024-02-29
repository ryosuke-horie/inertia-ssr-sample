<?php

declare(strict_types=1);

namespace App\Http\Controllers\BusinessOperator\AdminStaff;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessOperator\Auth\AdminStaffChangeEmailRequest;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\AdminStaffService;
use App\Trais\FlashMessageTrait;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class AdminStaffChangeEmailController extends Controller
{
    use FlashMessageTrait;

    private AdminStaffService $adminStaffService;

    public function __construct(AdminStaffService $adminStaffService)
    {
        $this->adminStaffService = $adminStaffService;
    }

    /**
     * 管理者スタッフメールアドレス変更表示
     * @param int $adminStaffId
     * @return Response
     */
    public function show(int $adminStaffId): Response
    {
        return Inertia::render('BusinessOperator/Staff/Admin/ChangeEmail/Show', [
            'adminStaffId' => $adminStaffId,
        ]);
    }

    /**
     * 管理者スタッフメールアドレス更新
     * @param int $adminStaffId
     * @param AdminStaffChangeEmailRequest $request
     * @return RedirectResponse
     */
    public function update(int $adminStaffId, AdminStaffChangeEmailRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($adminStaffId, $request) {
                $email = $request->input('email');
                $this->adminStaffService->updateEmail($adminStaffId, $email);
            });
            $this->flashMessageSuccess('メールアドレスを更新しました');
            return Redirect::route('business-operator.staff.admin.show', ['adminStaffId' => $adminStaffId]);
        } catch (\Exception $e) {
            $this->flashMessageFailed();
            return Redirect::route('business-operator.staff.admin.show', ['adminStaffId' => $adminStaffId]);
        }
    }
}
