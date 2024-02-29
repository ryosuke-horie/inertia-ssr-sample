<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Trais\StripeTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class PaymentInfoController extends Controller
{
    use StripeTrait;

    /**
     * 支払い情報の詳細画面を表示
     * @return Response
     */
    public function showPaymentMethod(): Response
    {
        try {
            $stripeId = Auth::guard('user')->user()->stripe_id;
            $paymentMethod = $this->getPaymentMethodByStripeId($stripeId);
            return Inertia::render('User/PaymentInfo/show', [
                'paymentMethod' => [
                    'brand' => $paymentMethod->card['brand'],
                    'last4' => $paymentMethod->card['last4'],
                    'expiry' => $this->getExpiry($paymentMethod->card['exp_month'], $paymentMethod->card['exp_year']),
                ],
            ]);
        } catch (\Exception $e) {
            return Inertia::render('User/PaymentInfo/show', [
                'paymentMethod' => null,
            ]);
        }
    }


    /**
     * 支払い情報の登録画面を表示
     * @return Response
     */
    public function createPaymentMethod(): Response
    {
        return Inertia::render('User/PaymentInfo/create', ['stripeKey' => config('app.stripe_key')]);
    }


    /**
     * 支払い情報の確認画面を表示
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function confirmPaymentMethod(Request $request): Response|RedirectResponse
    {
        try {
            return Inertia::render('User/PaymentInfo/confirm', [
                'token' => $request->token,
                'brand' => $request->brand,
                'last4' => $request->last4,
                'expiry' => $this->getExpiry($request->expMonth, $request->expYear),
            ]);
        } catch (\Exception $e) {
            return Redirect::route('user.payment-info.show');
        }
    }

    /**
     * 支払い方法を登録
     * @param Request $request
     * @return RedirectResponse
     */
    public function registerPaymentMethod(Request $request): RedirectResponse
    {
        try {
            $stripeId = Auth::guard('user')->user()->stripe_id;
            $this->storePaymentMethod($stripeId, $request->input('token'));
            return Redirect::route('user.payment-info.show');
        } catch (\Exception $e) {
            return Redirect::route('user.payment-info.show');
        }
    }

    /**
     * 支払い情報削除
     * @return RedirectResponse
     */
    public function deletePaymentMethod(): RedirectResponse
    {
        $stripeId = Auth::guard('user')->user()->stripe_id;
        $this->detachPaymentMethod($stripeId);
        return Redirect::route('user.payment-info.show', [
            'paymentMethod' => null,
        ]);
    }
}
