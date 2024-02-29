<?php

namespace App\Http\Controllers\BusinessOperator;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessOperator\ProfileUpdateRequest;
use App\Http\Requests\BusinessOperator\BusinessChangeEmailRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Services\BusinessOperatorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\BusinessOperator\BusinessChangePasswordRequest;

class ProfileController extends Controller
{
    private BusinessOperatorService $businessOperatorService;

    public function __construct(
        BusinessOperatorService $businessOperatorService
    ) {
        $this->businessOperatorService = $businessOperatorService;
    }

    /**
     * プロフィール表示
     * @return Response
     */
    public function edit(): Response
    {
        // 事業者プロフィール情報取得
        $args = $this->businessOperatorService->Profile(Auth::guard('business-operator')->user()->business_id);

        return Inertia::render('BusinessOperator/Profile/Edit', $args);
    }

    /**
     * プロフィール更新
     * @param ProfileUpdateRequest $request
     *
     * @return RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            // 事業者プロフィール情報更新
            $this->businessOperatorService->updateProfile(Auth::guard('business-operator')->user()->business_id, $request);

            return Redirect::route('business-operator.profile.edit')->with('success', 'プロフィールを更新しました');
        } catch (\Exception $e) {
            return Redirect::route('business-operator.profile.edit')->with('fail', 'プロフィール更新に失敗しました。');
        }
    }

    /**
     * API:プロフィール画像更新
     * @param Request $request
     * @return JsonResponse
     */
    public function updateProfileImage(Request $request): JsonResponse
    {
        $businessId = Auth::guard('business-operator')->user()->business_id;
        $args = $this->businessOperatorService->updateProfileImage($businessId, $request);
        return response()->json($args);
    }

    /**
     * API:プロフィール画像削除
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteProfileImage(Request $request): JsonResponse
    {
        $businessId = Auth::guard('business-operator')->user()->business_id;
        $args = $this->businessOperatorService->deleteProfileImage($businessId, $request);
        return response()->json($args);
    }

    /**
     * メールアドレス変更画面表示
     * @return Response
     */
    public function showChangeEmail(): Response
    {
        return Inertia::render('BusinessOperator/Profile/ChangeEmail/Show');
    }

    /**
     * メールアドレス変更メール送信
     * @return RedirectResponse
     */
    public function emailCreate(BusinessChangeEmailRequest $request): RedirectResponse
    {
        $email = $request->input('email');

        $this->businessOperatorService->registerTokenForEmail(
            Auth::guard('business-operator')->user()->business_id,
            Auth::guard('business-operator')->user()->business_name,
            $email
        );
        return Redirect::route('business-operator.profile.change-email.show');
    }

    /**
     * パスワード変更画面表示
     * @return Response
     */
    public function showChangePassword(): Response
    {
        return Inertia::render('BusinessOperator/Profile/ChangePassword/Show');
    }

    /**
     * パスワード変更処理
     *
     */
    public function passwordUpdate(BusinessChangePasswordRequest $request): RedirectResponse
    {
        $businessId = Auth::guard('business-operator')->user()->business_id;
        $password = $request->input('password');

        $this->businessOperatorService->updatePassword($businessId, $password);

        return Redirect::route('business-operator.profile.edit');
    }
}
