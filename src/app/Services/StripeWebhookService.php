<?php

namespace App\Services;

use App\Domain\UserTip\UserTipUpdate;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Admin\AdminRepositoryInterface;
use App\Repositories\Point\PointRepositoryInterface;
use App\Repositories\PointFluctuationHistory\PointFluctuationHistoryRepositoryInterface;
use App\Enums\EntityType;
use App\Repositories\Staff\StaffRepositoryInterface;
use App\Repositories\TotalTip\TotalTipRepositoryInterface;
use App\Repositories\UserTip\UserTipRepositoryInterface;
use App\Trais\StripeTrait;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;

class StripeWebhookService
{
    use StripeTrait;

    protected UserRepositoryInterface $userRepository;
    protected AdminRepositoryInterface $adminRepository;
    protected PointRepositoryInterface $pointRepository;
    protected PointFluctuationHistoryRepositoryInterface $pointFluctuationHistoryRepository;
    protected UserTipRepositoryInterface $userTipRepository;
    protected StaffRepositoryInterface $staffRepository;
    protected TotalTipRepositoryInterface $totalTipRepository;
    protected UserTipUpdate $userTipUpdate;

    public function __construct(
        UserRepositoryInterface $userRepository,
        AdminRepositoryInterface $adminRepository,
        PointRepositoryInterface $pointRepository,
        PointFluctuationHistoryRepositoryInterface $pointFluctuationHistoryRepository,
        UserTipRepositoryInterface $userTipRepository,
        StaffRepositoryInterface $staffRepository,
        TotalTipRepositoryInterface $totalTipRepository,
        UserTipUpdate $userTipUpdate
    ) {
        $this->userRepository = $userRepository;
        $this->adminRepository = $adminRepository;
        $this->pointRepository = $pointRepository;
        $this->pointFluctuationHistoryRepository = $pointFluctuationHistoryRepository;
        $this->userTipRepository = $userTipRepository;
        $this->staffRepository = $staffRepository;
        $this->totalTipRepository = $totalTipRepository;
        $this->userTipUpdate = $userTipUpdate;
    }

    /**
     * stripe payment intent決済完了
     * @param PaymentIntent $paymentIntent
     * @return void
     */
    public function paymentIntentSucceeded(PaymentIntent $paymentIntent): void
    {
        // PaymentIntent metadataに「nickname」があれば、ゲストユーザー
        $nickname = $paymentIntent->metadata['nickname'] ?? null;

        if (is_null($nickname)) {
            $this->userPaymentIntentSucceeded($paymentIntent);
        } else {
            $this->guestPaymentIntentSucceeded($paymentIntent);
        }
    }

    /**
     * 投げ銭ユーザーポイントチャージ
     * @param PaymentIntent $paymentIntent
     * @return void
     */
    private function userPaymentIntentSucceeded(PaymentIntent $paymentIntent): void
    {
        $paymentIntentId = $paymentIntent->id;
        // 購入金額(有償ポイント)
        $buyAmount = $paymentIntent->amount;
        // 無償ポイント
        $buyFreeAmount = $this->adminRepository->getFreeAmountForAmount($buyAmount);
        // 顧客情報からmetadataの投げ銭ユーザーID取得(なければゲストユーザー：１)
        $userId = $this->getCustomer($paymentIntent->customer)->metadata['user_id'] ?? 1;
        // 有効期限
        $expirationDate = date('Y-m-d H:i:s', strtotime('+6 month'));

        // ポイント購入履歴を作成
        $this->pointRepository->createPointBuyHistory($userId, $buyAmount, $paymentIntentId, true, $expirationDate);
        if ($buyFreeAmount > 0) {
            $this->pointRepository->createPointBuyHistory($userId, $buyFreeAmount, $paymentIntentId, false, $expirationDate);
        }

        // ポイント変動履歴作成
        $this->pointFluctuationHistoryRepository->createPointFluctuationHistory($userId, EntityType::User, $buyAmount, 2);

        // ユーザー情報のポイントを更新
        $user = $this->userRepository->findByUserId($userId);
        $totalPaidPoints = $user->paid_points + $buyAmount;
        $totalFreePoints = $user->free_points + $buyFreeAmount;
        $totalAmount = $buyAmount + $buyFreeAmount;
        $this->userRepository->updateUserPoints($userId, $totalFreePoints, $totalPaidPoints, $totalAmount);
    }

    /**
     * ゲスト投げ銭
     * @param PaymentIntent $paymentIntent
     * @return void
     */
    private function guestPaymentIntentSucceeded(PaymentIntent $paymentIntent): void
    {
        // 決済番号
        $paymentIntentId = $paymentIntent->id;
        // 購入金額
        $buyAmount     = $paymentIntent->amount;
        // ゲストユーザーID
        $userId = 1;
        // 有効期限
        $expirationDate = date('Y-m-d H:i:s', strtotime('+6 month'));

        // ポイント購入履歴を作成
        $this->pointRepository->createPointBuyHistory($userId, $buyAmount, $paymentIntentId, true, $expirationDate);

        // ポイント変動履歴作成
        $this->pointFluctuationHistoryRepository->createPointFluctuationHistory($userId, EntityType::User, $buyAmount, 2);

        // 投げ銭登録
        $metadata = $paymentIntent->metadata;
        $staffId = $metadata['staffId'] ?? 0;
        $nickname = $metadata['nickname'] ?? null;
        $message  = $metadata['message'] ?? null;
        $deskNumber = 0;
        $userTipId = $this->userTipRepository->createUserTip($staffId, $userId, $message, $buyAmount, $deskNumber, $nickname);

        // ポイント購入履歴更新、ポイント利用履歴登録
        $pointDetails = $this->pointRepository->getUsablePointBuyHistoryByUserId($userId);
        $deductedPoints = $this->userTipUpdate->deductPointsFromDetails($pointDetails, $buyAmount);
        foreach ($deductedPoints as $points) {
            $this->pointRepository->updateRemainingPoints($points['buyId'], $points['remainingPoints'], $points['isPointUsable']);
            $this->pointRepository->createPointUsageHistory($points['buyId'], $userTipId, $points['deductedAmount']);
        }

        // ポイント変動履歴作成（ユーザー・スタッフ）
        $this->pointFluctuationHistoryRepository->createPointFluctuationHistory($staffId, EntityType::Staff, $buyAmount, 1);
        $this->pointFluctuationHistoryRepository->createPointFluctuationHistory($userId, EntityType::User, $buyAmount, 1);

        // スタッフ情報のポイントを更新
        $this->staffRepository->updateStaffPointsByStaffId($staffId, $buyAmount);

        // 累計投げ銭を月ごとに作成・更新
        $this->totalTipRepository->updateOrCreateTotalTip(EntityType::Staff, $staffId, date('Y-m'), $buyAmount);
    }
}
