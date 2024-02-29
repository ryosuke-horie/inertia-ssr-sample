<?php

namespace App\Http\Controllers\GuestUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuestUser\CreatePaymentIntentRequest;
use App\Services\GuestUserService;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\GuestUser\PaymentRequest;
use App\Trais\StripeTrait;

class StaffController extends Controller
{
    use StripeTrait;

    private GuestUserService $guestUserService;

    public function __construct(GuestUserService $guestUserService)
    {
        $this->guestUserService = $guestUserService;
    }

    /**
     * スタッフ一覧
     *
     * @return Response
     */
    public function index(int $businessId): Response
    {
        $args = $this->guestUserService->getBusinessOperatorShowWithStaffDetail($businessId);
        return Inertia::render('GuestUser/BusinessOperator/Staff/Index', $args);
    }

    /**
     * スタッフ詳細
     *
     * @param int $businessId
     * @return Response
     */
    public function show(int $businessId, int $staffId): Response
    {
        $userId = 1;
        $args = $this->guestUserService->getStaffDetail($userId, $businessId, $staffId);
        return Inertia::render('GuestUser/BusinessOperator/Staff/Show', $args);
    }

    /**
     * 投げ銭決済
     * @param int $businessId
     * @param int $staffId
     * @param PaymentRequest $request
     * @return Response|RedirectResponse
     */
    public function payment(int $businessId, int $staffId, PaymentRequest $request): Response|RedirectResponse
    {
        $refererUrl = $request->header('referer');
        $checkUrl = config('app.url') . "/guest/business-operator/{$businessId}/staff/{$staffId}";
        if (!str_starts_with($refererUrl, $checkUrl)) {
            return Redirect::route('guest.business-operator.staff.index', [
                'businessId' => $businessId,
            ]);
        }

        return Inertia::render('GuestUser/BusinessOperator/Staff/UserTipPayment', [
            'stripeKey'     => config('app.stripe_key'),
            'staffId'       => $staffId,
            'businessId'    => $businessId,
            'amount'        => $request->amount,
            'freeAmount'    => $request->freeAmount,
            'message'       => $request->message,
            'staffName'     => $request->staffName,
            'guestNickname' => $request->guestNickname,
        ]);
    }

    /**
     * 投げ銭登録
     * @param int $businessId
     * @param int $staffId
     * @param PaymentRequest $request
     * @return Response|RedirectResponse
     */
    public function paymentComplete(int $businessId, int $staffId, Request $request): Response|RedirectResponse
    {
        $refererUrl = $request->header('referer');
        $checkUrl = config('app.url') . "/guest/business-operator/{$businessId}/staff/{$staffId}/payment";
        if (!str_starts_with($refererUrl, $checkUrl)) {
            return Redirect::route('guest.business-operator.staff.index', [
                'businessId' => $businessId,
            ]);
        }

        return Inertia::render('GuestUser/BusinessOperator/Staff/UserTipComplete', [
            'businessId' => $businessId,
            'staffId' => $staffId,
        ]);
    }

    /**
     * 事業者に紐づくスタッフの存在チェック（API）
     * (Middlewareで存在チェック)
     * @param int $staffId
     * @param int $businessId
     * @return JsonResponse
     */
    public function exists(int $businessId, int $staffId): JsonResponse
    {
        return response()->json(['response' => true]);
    }

    /**
     * 支払い意向を登録（API）
     * @param int $staffId
     * @param int $businessId
     * @param CreatePaymentIntentRequest $request
     * @return JsonResponse
     */
    public function createPaymentIntent(int $businessId, int $staffId, CreatePaymentIntentRequest $request): JsonResponse
    {
        $amount = $request->amount;
        $stripeId = $this->guestUserService->getGuestStripeId();
        $metadata = [
            'staffId' => $staffId,
            'nickname' => $request->guestNickname,
            'message' => $request->message ?? ''
        ];
        $paymentIntent = $this->storePaymentIntentGuestUser($amount, $stripeId, $metadata);
        return response()->json([
            'clientSecret' => $paymentIntent->client_secret
        ]);
    }
}
