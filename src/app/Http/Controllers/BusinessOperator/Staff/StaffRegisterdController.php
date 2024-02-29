<?php

declare(strict_types=1);

namespace App\Http\Controllers\BusinessOperator\Staff;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BusinessOperator\StaffCreateRequest;
use App\Services\StaffService;
use App\Trais\FlashMessageTrait;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class StaffRegisterdController extends Controller
{
    use FlashMessageTrait;

    private StaffService $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    /**
     * スタッフ新規登録用トークン作成画面
     * @return Response
     */
    public function show(): Response
    {
        return Inertia::render('BusinessOperator/Staff/Create');
    }

    /**
     * スタッフ新規登録用トークン登録
     * @param StaffCreateRequest $request
     * @return RedirectResponse
     */
    public function create(StaffCreateRequest $request): RedirectResponse
    {
        try {
            $hasEmail = DB::transaction(function () use ($request) {
                $businessId = Auth::guard('business-operator')->user()->business_id;
                $staffs = $request->input('staffs');
                return $this->staffService->registerTokenForStaffs($businessId, $staffs);
            });

            $hasEmail
                ? $this->flashMessageSuccess('新規登録用メールを送信しました。')
                : $this->flashMessageSuccess('スタッフ登録完了しました。');
            return Redirect::route('business-operator.staff.index');
        } catch (\Exception $e) {
            $this->flashMessageFailed();
            return Redirect::route('business-operator.staff.index');
        }
    }
}
