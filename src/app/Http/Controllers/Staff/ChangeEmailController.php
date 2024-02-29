<?php

declare(strict_types=1);

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\Staff\ChangeEmailRequest;
use App\Services\StaffService;
use App\Trais\FlashMessageTrait;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ChangeEmailController extends Controller
{
    use FlashMessageTrait;

    private StaffService $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    /**
     * メールアドレス変更用トークン作成画面表示
     * @return Response
     */
    public function show(): Response
    {
        return Inertia::render('Staff/Profile/ChangeEmail/Show');
    }

    /**
     * メールアドレス変更用トークン作成
     * @param ChangeEmailRequest $request
     * @return RedirectResponse
     */
    public function create(ChangeEmailRequest $request): RedirectResponse
    {
        try {
            $staff = Auth::guard('staff')->user();
            $staffId = $staff->staff_id;
            $staffName = $staff->staff_name;
            $email = $request->email;
            $this->staffService->registerTokenForChangeEmail($staffId, $email, $staffName);
            $this->flashMessageSuccess('メールアドレス変更メールを送信しました。');
            return Redirect::route('staff.profile.show');
        } catch (\Exception $e) {
            return Redirect::route('staff.profile.show');
        }
    }
}
