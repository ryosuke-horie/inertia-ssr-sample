<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Illuminate\Http\JsonResponse;

class PaymentInfoService
{
    protected $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * Stripeの公開鍵取得
     *
     * @return string
     */
    public function getStripeKey(): string
    {
        return config('app.stripe_key');
    }

    /**
     * 支払い方法を取得
     *
     * @param string $stripeId Stripeの顧客ID
     * @return array Stripeから取得した支払い方法の配列
     * @throws ApiErrorException Stripe APIの呼び出しで問題が発生した場合にスロー
     */
    public function getPaymentMethods(string $stripeId): array
    {
        Stripe::setApiKey(\config('stripe.secret'));
        return $this->userRepository->getPaymentMethods($stripeId);
    }
}
