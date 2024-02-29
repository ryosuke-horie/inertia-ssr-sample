<?php

declare(strict_types=1);

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\StaffService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewEmailController extends Controller
{
    private StaffService $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    /**
     * メールアドレス変更
     * トークン管理のtokenとemailが一致した場合、更新完了ページへリダイレクト
     * @param Request $request
     * @return RedirectResponse
     */
    public function check(Request $request): RedirectResponse
    {
        $token = $request->input('token');
        $email = $request->input('email');

        try {
            DB::transaction(function () use ($token, $email) {
                $this->staffService->updateEmailByTokenAndEmail($token, $email);
            });
            // メールアドレス変更完了ページに遷移するためのセッション
            session(['previousName' => 'staff.change-email.check']);
            return Redirect::route('staff.change-email.complete');
        } catch (\Exception $e) {
            return Redirect::route('staff.login');
        }
    }

    /**
     * メールアドレス変更完了画面表示
     * @return Response|RedirectResponse
     */
    public function complete(): Response|RedirectResponse
    {
        // /staff/change-emailからのリダイレクト以外はログインページに遷移
        $previousUrl = session('previousName');
        session()->forget('previousName');

        if ($previousUrl !== 'staff.change-email.check') {
            return Redirect::route('staff.login');
        }

        return Inertia::render('Staff/Auth/ChangeEmail/Complete');
    }
}
