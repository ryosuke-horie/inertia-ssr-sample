<?php

declare(strict_types=1);

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\ChangePasswordRequest;
use App\Services\StaffService;
use App\Trais\FlashMessageTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Response;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;

class ChangePasswordController extends Controller
{
    use FlashMessageTrait;

    private StaffService $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    /**
     * パスワード変更画面表示
     * @return Response
     */
    public function show(): Response
    {
        return Inertia::render('Staff/Profile/ChangePassword/Show');
    }

    /**
     * パスワード更新
     * @param ChangePasswordRequest $request
     * @return RedirectResponse
     */
    public function update(ChangePasswordRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $staffId = Auth::guard('staff')->user()->staff_id;
                $this->staffService->updatePassword($staffId, $request->password);
            });
            $this->flashMessageSuccess('パスワードを変更しました');
            return Redirect::route('staff.profile.show');
        } catch (\Exception $e) {
            return Redirect::route('staff.profile.show');
        }
    }
}
