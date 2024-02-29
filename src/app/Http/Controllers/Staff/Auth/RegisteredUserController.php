<?php

declare(strict_types=1);

namespace App\Http\Controllers\Staff\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StaffRegisterRequest;
use App\Services\StaffService;
use App\Trais\FlashMessageTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    use FlashMessageTrait;

    private StaffService $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    /**
     * スタッフ新規登録画面表示
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $token = $request->input('token');

        return Inertia::render('Staff/Auth/Register', [
            'name'  => $name,
            'email' => $email,
            'token' => $token
        ]);
    }

    /**
     * スタッフ新規登録
     * @param StaffRegisterRequest $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(StaffRegisterRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $token     = $request->input('token');
                $staffName = $request->input('staffName');
                $email     = $request->input('email');
                $password  = $request->input('password');
                $this->staffService->registerStaff($token, $staffName, $email, $password);
            });
            $this->flashMessageSuccess('スタッフ新規登録完了しました');
            return Redirect::route('staff.login');
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'token' => '予期せぬエラーが発生しました',
            ]);
        }
    }
}
