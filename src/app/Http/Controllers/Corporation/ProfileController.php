<?php

namespace App\Http\Controllers\Corporation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Corporation\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\Corporation\CorporationChangeEmailRequest;
use App\Http\Requests\Corporation\CorporationChangePasswordRequest;
use App\Services\CorporationService;
use App\Trais\FlashMessageTrait;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    use FlashMessageTrait;

    private CorporationService $corporationService;

    public function __construct(
        CorporationService $corporationService
    ) {
        $this->corporationService = $corporationService;
    }

    /**
     * プロフィール画面表示
     * @return Response
     */
    public function edit(): Response
    {
        return Inertia::render('Corporation/Profile/Edit', [
            'corporationName'   => Auth::guard('corporation')->user()->corporation_name,
            'email'             => Auth::guard('corporation')->user()->email
        ]);
    }

    /**
     * メールアドレス変更画面表示
     * @return Response
     */
    public function showChangeEmail(): Response
    {
        return Inertia::render('Corporation/Profile/ChangeEmail/Show');
    }

    /**
     * メールアドレス変更メール送信
     * @param CorporationChangeEmailRequest $request
     * @return RedirectResponse
     */
    public function emailCreate(CorporationChangeEmailRequest $request): RedirectResponse
    {
        $email = $request->input('email');

        $this->corporationService->registerTokenForEmail(
            Auth::guard('corporation')->user()->corporation_id,
            Auth::guard('corporation')->user()->corporation_name,
            $email
        );

        $this->flashMessageSuccess('新しいメールアドレスへ確認メールを送信しました');
        return Redirect::route('corporation.profile.change-email.show');
    }

    /**
     * パスワード変更画面表示
     * @return Response
     */
    public function showChangePassword(): Response
    {
        return Inertia::render('Corporation/Profile/ChangePassword/Show');
    }

    /**
     * パスワード変更処理
     * @param CorporationChangePasswordRequest $request
     * @return RedirectResponse
     */
    public function passwordUpdate(CorporationChangePasswordRequest $request): RedirectResponse
    {
        $corporationId = Auth::guard('corporation')->user()->corporation_id;
        $password = $request->input('password');

        $this->corporationService->updatePassword($corporationId, $password);

        return Redirect::route('corporation.profile.edit');
    }
}
