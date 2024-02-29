<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreatePaymentIntentRequest;
use App\Http\Requests\User\PaymentSelectRequest;
use App\Services\PointChargeService;
use App\Trais\StripeTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PointChargeController extends Controller
{
    use StripeTrait;

    private PointChargeService $pointChargeService;

    public function __construct(PointChargeService $pointChargeService)
    {
        $this->pointChargeService = $pointChargeService;
    }

    /**
     * ポイント購入一覧画面表示
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        // URLが投げ銭画面・ポイント購入系画面以外はセッション削除
        $previousUrl = $request->headers->get('referer');
        if (strpos($previousUrl, 'point-charge') === false && strpos($previousUrl, 'staff') === false) {
            $request->session()->forget('tipUrl');
        }

        $user = Auth::guard('user')->user();
        $freePoint   = $user->free_points;
        $paidPoint   = $user->paid_points;
        $totalAmount = $user->total_amount;
        $age         = $user->age; // アクセサで取得
        $args        = $this->pointChargeService->getPointList($freePoint, $paidPoint, $totalAmount, $age);
        return Inertia::render('User/PointCharge/Index', $args);
    }

    /**
     * 支払い方法選択画面表示
     *
     * @return Response|RedirectResponse
     */
    public function paymentSelect(PaymentSelectRequest $request): Response|RedirectResponse
    {
        $refererUrl = $request->header('referer');
        $checkUrl = config('app.url') . '/user/point-charge';
        if ($refererUrl !== $checkUrl) {
            return Redirect::route('user.point-charge.index');
        }

        $stripeId = Auth::guard('user')->user()->stripe_id;
        $paymentMethod = $this->getPaymentMethodByStripeId($stripeId);

        // クレジットカード登録していない場合
        if (is_null($paymentMethod)) {
            return Inertia::render('User/PointCharge/Select/Show', [
                'amount'      => $request->amount,
                'freeAmount'  => $request->freeAmount,
                'stripeKey'   => config('app.stripe_key'),
            ]);
        }

        // クレジットカード登録している場合
        return Inertia::render('User/PointCharge/Select/ShowExistCard', [
            'amount'      => $request->amount,
            'freeAmount'  => $request->freeAmount,
            'paymentMethod' => [
                'brand' => $paymentMethod->card['brand'],
                'last4' => $paymentMethod->card['last4'],
                'expiry' => $this->getExpiry($paymentMethod->card['exp_month'], $paymentMethod->card['exp_year'])
            ],
        ]);
    }

    /**
     * 支払い完了画面
     * 1: ポイント購入一覧画面から遷移 →マイページ画面に遷移
     * 2: 投げ銭画面から遷移→完了画面から投げ銭画面（スタッフ詳細画面）に遷移
     *
     * @return Response|RedirectResponse
     */
    public function paymentComplete(Request $request): Response|RedirectResponse
    {
        $refererUrl = $request->header('referer');
        $checkUrl = config('app.url') . '/user/point-charge/payment-select';
        if ($refererUrl !== $checkUrl) {
            return Redirect::route('user.point-charge.index');
        }

        return Inertia::render('User/PointCharge/PaymentComplete', [
            'tipUrl' => session('tipUrl') ?? null,
        ]);
    }

    /**
     * 支払い完了画面
     * 1: ポイント購入一覧画面から遷移(クレジット登録している場合) →マイページ画面に遷移
     * 2: 投げ銭画面から遷移→完了画面から投げ銭画面（スタッフ詳細画面）に遷移
     *
     * @return Response|RedirectResponse
     */
    public function paymentCompleteExistCard(Request $request): Response|RedirectResponse
    {
        $refererUrl = $request->header('referer');
        $checkUrl = config('app.url') . '/user/point-charge/payment-select';
        if ($refererUrl !== $checkUrl) {
            return Redirect::route('user.point-charge.index');
        }

        try {
            $amount = $request->amount;
            $stripeId = Auth::guard('user')->user()->stripe_id;
            $paymentIntent = $this->storePaymentIntentExistCard($amount, $stripeId);
            $paymentIntent->confirm();
            return Inertia::render('User/PointCharge/PaymentComplete', [
                'tipUrl' => session('tipUrl') ?? null,
            ]);
        } catch (\Stripe\Exception\CardException $e) {
            return Redirect::route('user.point-charge.index');
        }
    }


    /**
     * GETリクエストの場合に一覧表示画面に遷移
     */
    public function handleGetOnPayment()
    {
        return Inertia::location(route('user.point-charge.index'));
    }

    /**
     * 支払い意向を登録（API）
     *
     * @param CreatePaymentIntentRequest $request
     * @return JsonResponse
     */
    public function createPaymentIntent(CreatePaymentIntentRequest $request): JsonResponse
    {
        $amount = $request->amount;
        $stripeId = Auth::guard('user')->user()->stripe_id;
        $paymentIntent = $this->storePaymentIntent($amount, $stripeId);
        $args = ['clientSecret' => $paymentIntent->client_secret];
        return response()->json($args);
    }
}
