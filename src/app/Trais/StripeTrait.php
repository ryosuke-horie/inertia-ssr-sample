<?php

declare(strict_types=1);

namespace App\Trais;

use Stripe\Stripe;
use Stripe\PaymentMethod;
use Stripe\Customer;
use Stripe\PaymentIntent;

trait StripeTrait
{
    /**
     * Stripe顧客情報作成
     * @param int $userId
     * @param string $userName
     * @param string $email
     * @return Customer
     */
    public function storeCustomer(int $userId, string $userName, string $email): Customer
    {
        $this->setStripeKey();
        return Customer::create([
            'name' => $userName,
            'email' => $email,
            'metadata' => [
                'user_id' => $userId,
            ],
        ]);
    }

    /**
     * Stripe顧客情報取得
     * @param string $stripeId
     * @return Customer
     */
    public function getCustomer(string $stripeId): Customer
    {
        $this->setStripeKey();
        return Customer::retrieve($stripeId);
    }

    /**
     * Stripe顧客情報更新
     * @param string $stripeId
     * @param array $params
     * @return void
     */
    public function updateCustomer(string $stripeId, array $params): void
    {
        $this->setStripeKey();
        Customer::update($stripeId, $params);
    }

    /**
     * Stripe支払い方法取得
     * @param string $paymentMethodId
     */
    public function getPaymentMethodByPaymentMethodId(string $paymentMethodId)
    {
        $this->setStripeKey();
        return PaymentMethod::retrieve($paymentMethodId);
    }

    /**
     * Stripe支払い方法取得
     * @param string $stripeId
     * @return PaymentMethod|null
     */
    public function getPaymentMethodByStripeId(string $stripeId): PaymentMethod|null
    {
        $this->setStripeKey();
        $customer = Customer::retrieve($stripeId);
        return $customer->default_source ? PaymentMethod::retrieve($customer->default_source) : null;
    }

    /**
     * Stripe支払い方法作成
     * @param string $stripeId
     * @param string $token (stripeJSで生成したトークン)
     * @return void
     */
    public function storePaymentMethod(string $stripeId, string $token): void
    {
        $this->setStripeKey();
        Customer::update($stripeId, ['source' => $token]);
    }

    /**
     * Stripe支払い方法削除
     * @param string $stripeId
     * @return void
     */
    public function detachPaymentMethod(string $stripeId): void
    {
        $this->setStripeKey();
        $customer = Customer::retrieve($stripeId);
        $paymentMethod = PaymentMethod::retrieve($customer->default_source);
        $paymentMethod->detach();
    }

    /**
     * 支払い情報取得
     * @param string $paymentIntentId
     * @return PaymentIntent
     */
    public function getPaymentIntent(string $paymentIntentId): PaymentIntent
    {
        $this->setStripeKey();
        return PaymentIntent::retrieve($paymentIntentId);
    }

    /**
     * 支払い意向（PaymentIntent）作成：ゲストユーザー用
     * @param int $amount 支払い金額
     * @param string $stripeId stripe顧客ID
     * @param array $metadata
     * @param ?string $currency 通貨コード（デフォルトは 'jpy'）
     * @return PaymentIntent 作成されたPaymentIntentオブジェクト
     */
    public function storePaymentIntentGuestUser(int $amount, string $stripeId, array $metadata, ?string $currency = 'jpy'): PaymentIntent
    {
        $this->setStripeKey();
        $attributes = $this->storePaymentIntentAttributes($amount, $stripeId, $metadata, $currency);
        return PaymentIntent::create($attributes);
    }

    /**
     * 支払い意向（PaymentIntent）作成：投げ銭ユーザー用
     * @param int $amount 支払い金額
     * @param string $stripeId stripe顧客ID
     * @param ?string $currency 通貨コード（デフォルトは 'jpy'）
     * @return PaymentIntent 作成されたPaymentIntentオブジェクト
     */
    public function storePaymentIntent(int $amount, string $stripeId, ?string $currency = 'jpy'): PaymentIntent
    {
        $this->setStripeKey();
        $attributes = $this->storePaymentIntentAttributes($amount, $stripeId, [], $currency);
        return PaymentIntent::create($attributes);
    }

    /**
     * 支払い意向（PaymentIntent）作成(クレジット登録している場合)
     * @param int $amount 支払い金額
     * @param string $stripeId stripe顧客ID
     * @param ?string $currency 通貨コード（デフォルトは 'jpy'）
     * @return PaymentIntent 作成されたPaymentIntentオブジェクト
     */
    public function storePaymentIntentExistCard(int $amount, string $stripeId, ?string $currency = 'jpy')
    {
        $this->setStripeKey();
        $attributes = $this->storePaymentIntentAttributes($amount, $stripeId, [], $currency);
        $paymentMethod = $this->getPaymentMethodByStripeId($stripeId);

        if (!is_null($paymentMethod)) {
            $attributes += ['payment_method' => $paymentMethod->id];
            return PaymentIntent::create($attributes);
        }

        throw new \Stripe\Exception\CardException('test');
    }

    /**
     * 有効期限を??/??形式で取得
     * @param int $expMonth
     * @param int $expYear
     */
    public function getExpiry(int $expMonth, int $expYear): string
    {
        $formatMonth = str_pad((string) $expMonth, 2, '0', STR_PAD_LEFT);
        $formatYear = substr((string) $expYear, 2);
        return $formatMonth . ' / ' . $formatYear;
    }

    /**
     * StripeAPI利用のための鍵をセット
     * @return void
     */
    private function setStripeKey(): void
    {
        Stripe::setApiKey(config('app.stripe_secret_key'));
    }

    /**
     * paymentIntentで利用するattributesを取得
     * @param int $amount 支払い金額
     * @param string $stripeId stripe顧客ID
     * @param ?array $metadata
     * @param ?string $currency 通貨コード（デフォルトは 'jpy'）
     * @return array
     */
    private function storePaymentIntentAttributes(int $amount, string $stripeId = null, ?array $metadata = [], ?string $currency = 'jpy'): array
    {
        $attributes = [
            'amount' => $amount,
            'currency' => $currency,
            'customer' => $stripeId,
            'automatic_payment_methods' => [
                'enabled' => true,
                'allow_redirects' => 'never',
            ],
            'metadata' => $metadata
        ];

        return $attributes;
    }
}
