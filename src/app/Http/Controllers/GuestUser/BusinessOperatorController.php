<?php

namespace App\Http\Controllers\GuestUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ReviewStoreRequest;
use App\Services\GuestUserService;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class BusinessOperatorController extends Controller
{
    private GuestUserService $guestUserService;

    public function __construct(
        GuestUserService $guestUserService
    ) {
        $this->guestUserService = $guestUserService;
    }

    /**
     * 事業者一覧
     *
     * @return Response
     */
    public function index(): Response
    {
        $args = $this->guestUserService->getBusinessOperatorList();
        return Inertia::render('GuestUser/BusinessOperator/Index', $args);
    }

    /**
     * 事業者詳細
     *
     * @param int $businessId
     * @return Response
     */
    public function show(int $businessId): Response
    {
        // 事業者詳細情報取得
        $args = $this->guestUserService->getBusinessOperatorShow($businessId);

        return Inertia::render('GuestUser/BusinessOperator/Show', [
            'userId' => 1,
            'businessId' => $businessId,
            ...$args
        ]);
    }

    /**
     * 口コミ投稿画面
     *
     * @param int $businessId
     * @return Response
     */
    public function createReview(int $businessId): Response
    {
        $userId = 1;
        $args   = $this->guestUserService->getUserProfileImage($userId);
        return Inertia::render('GuestUser/BusinessOperator/CreateReview', [
            ...$args,
            'businessId' => $businessId,
        ]);
    }

    /**
     * 口コミ登録
     *
     * @param int $businessId
     * @param ReviewStoreRequest $reviewStoreRequest
     * @return RedirectResponse
     */
    public function storeReview(int $businessId, ReviewStoreRequest $reviewStoreRequest): RedirectResponse
    {
        $userId = 1;
        $this->guestUserService->storeReview($businessId, $userId, $reviewStoreRequest->review);
        return Redirect::route('guest.business-operator.show', [
            'businessId' => $businessId,
        ]);
    }
}
