<?php

declare(strict_types=1);

namespace App\Http\Controllers\BusinessOperator\Staff;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\BusinessOperator\Auth\StaffChangeEmailRequest;
use App\Services\StaffService;
use App\Trais\FlashMessageTrait;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class StaffChangeEmailController extends Controller
{
    use FlashMessageTrait;

    private StaffService $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    /**
     * スタッフメールアドレス変更画面表示
     * @return Response
     */
    public function show(int $staffId): Response
    {
        return Inertia::render('BusinessOperator/Staff/Profile/ChangeEmail/Show', [
            'staffId' => $staffId,
        ]);
    }

    /**
     * スタッフメールアドレス更新
     * @param int $staffId
     * @param StaffChangeEmailRequest $request
     * @return RedirectResponse
     */
    public function update(int $staffId, StaffChangeEmailRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($staffId, $request) {
                $email = $request->email;
                $this->staffService->updateEmail($staffId, $email);
            });
            $this->flashMessageSuccess('メールアドレス変更を完了しました');
            return Redirect::route('business-operator.staff.show', ['staffId' => $staffId]);
        } catch (\Exception $e) {
            return Redirect::route('business-operator.staff.show', ['staffId' => $staffId]);
        }
    }
}
