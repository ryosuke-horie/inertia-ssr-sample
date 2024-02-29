<?php

declare(strict_types=1);

namespace App\Http\Controllers\AdminStaff\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminStaff\AdminStaffRegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\AdminStaffService;
use App\Trais\FlashMessageTrait;
use Illuminate\Support\Facades\DB;

class RegisteredAdminStaffController extends Controller
{
    use FlashMessageTrait;

    private AdminStaffService $adminStaffService;

    public function __construct(AdminStaffService $adminStaffService)
    {
        $this->adminStaffService = $adminStaffService;
    }

    /**
     * 管理者スタッフ新規登録画面表示
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $name = $request->input('name', '');
        $email = $request->input('email', '');
        $token = $request->input('token', '');
        $staffIds = explode(',', $request->input('ids', ''));

        return Inertia::render('AdminStaff/Auth/Register', [
            'name'  => $name,
            'email' => $email,
            'token' => $token,
            'staffIds' => $staffIds
        ]);
    }

    /**
     * 管理者スタッフ新規登録
     * @param AdminStaffRegisterRequest $request
     * @return RedirectResponse
     */
    public function store(AdminStaffRegisterRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $token     = $request->input('token');
                $name      = $request->input('name');
                $email     = $request->input('email');
                $staffIds  = $request->input('staffIds');
                $password  = $request->input('password');
                $this->adminStaffService->registerAdminStaff($token, $name, $email, $staffIds, $password);
            });
            $this->flashMessageSuccess('新規登録完了しました。');
            return Redirect::route('admin-staff.login');
        } catch (\Exception $e) {
            $this->flashMessageSuccess();
            return Redirect::route('admin-staff.login');
        }
    }
}
