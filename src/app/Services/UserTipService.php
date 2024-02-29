<?php

namespace App\Services;

use App\Domain\Tip\Tip\StaffTip;
use App\Domain\Tip\Tip\UserTip;
use App\Repositories\Admin\AdminRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Staff\StaffRepositoryInterface;
use App\Repositories\UserTip\UserTipRepositoryInterface;
use App\Repositories\Point\PointRepositoryInterface;
use App\Repositories\PointFluctuationHistory\PointFluctuationHistoryRepositoryInterface;
use App\Repositories\TotalTip\TotalTipRepositoryInterface;
use App\Enums\EntityType;
use App\Trais\FileTrait;
use App\Trais\StripeTrait;
use Illuminate\Support\Facades\DB;
use App\Domain\UserTip\UserTipUpdate;
use Carbon\Carbon;

class UserTipService
{
    use FileTrait;
    use StripeTrait;

    protected $adminRepository;
    protected $userRepository;
    protected $staffRepository;
    protected $userTipRepository;
    protected $pointRepository;
    protected $pointFluctuationHistoryRepository;
    protected $totalTipRepository;
    protected $userTipUpdate;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        AdminRepositoryInterface $adminRepository,
        UserRepositoryInterface $userRepository,
        StaffRepositoryInterface $staffRepository,
        UserTipRepositoryInterface $userTipRepository,
        PointRepositoryInterface $pointRepository,
        PointFluctuationHistoryRepositoryInterface $pointFluctuationHistoryRepository,
        TotalTipRepositoryInterface $totalTipRepository,
        UserTipUpdate $userTipUpdate
    ) {
        $this->adminRepository    = $adminRepository;
        $this->userRepository     = $userRepository;
        $this->staffRepository    = $staffRepository;
        $this->userTipRepository  = $userTipRepository;
        $this->pointRepository    = $pointRepository;
        $this->pointFluctuationHistoryRepository = $pointFluctuationHistoryRepository;
        $this->totalTipRepository = $totalTipRepository;
        $this->userTipUpdate      = $userTipUpdate;
    }

    /**
     * 投げ銭登録
     *
     * @param int $userId ユーザーID
     * @param int $staffId スタッフID
     * @param int $freePoints 無償ポイントの数
     * @param int $paidPoints 有償ポイントの数
     * @param string|null $message メッセージ
     * @param int $amount 金額
     * @param int $deskNumber 卓番号
     * @return void
     */
    public function updateUserTips(int $userId, int $staffId, int $freePoints, int $paidPoints, string|null $message, int $amount, int $deskNumber = 0): void
    {
        try {
            DB::transaction(function () use ($staffId, $userId, $message, $amount, $deskNumber, $freePoints, $paidPoints) {
                // 投げ銭登録
                $userTipId = $this->userTipRepository->createUserTip($staffId, $userId, $message, $amount, $deskNumber);

                // ポイント購入履歴更新、ポイント利用履歴登録
                $pointDetails = $this->pointRepository->getUsablePointBuyHistoryByUserId($userId);
                $deductedPoints = $this->userTipUpdate->deductPointsFromDetails($pointDetails, $amount);
                foreach ($deductedPoints as $points) {
                    $this->pointRepository->updateRemainingPoints($points['buyId'], $points['remainingPoints'], $points['isPointUsable']);
                    $this->pointRepository->createPointUsageHistory($points['buyId'], $userTipId, $points['deductedAmount']);
                }

                // ポイント変動履歴作成（ユーザー・スタッフ）
                $this->pointFluctuationHistoryRepository->createPointFluctuationHistory($userId, EntityType::User, $amount, 1);
                $this->pointFluctuationHistoryRepository->createPointFluctuationHistory($staffId, EntityType::Staff, $amount, 1);

                // ユーザー情報・スタッフ情報のポイントを更新
                $userPoints = $this->userTipUpdate->deductPoints($amount, $freePoints, $paidPoints);
                $this->userRepository->updateUserPoints($userId, $userPoints['freePoint'], $userPoints['paidPoint'], $amount);
                $this->staffRepository->updateStaffPointsByStaffId($staffId, $amount);

                // 累計投げ銭を月ごとに作成・更新
                $this->totalTipRepository->updateOrCreateTotalTip(EntityType::Staff, $staffId, date('Y-m'), $amount);
                $this->totalTipRepository->updateOrCreateTotalTip(EntityType::User, $userId, date('Y-m'), $amount);
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * ゲストのポイント、投げ銭登録
     *
     * @param int $userId ユーザーID
     * @param int $staffId スタッフID
     * @param string $paymentIntentId 支払い情報ID
     * @param ?string $message メッセージ
     * @param int $deskNumber 卓番号
     * @return void
     */
    public function updateGuestUserTips(int $userId, int $staffId, string $paymentIntentId, ?string $message, string $guestNickname, int $deskNumber = 0): void
    {
        try {
            DB::transaction(function () use ($staffId, $userId, $paymentIntentId, $message, $guestNickname, $deskNumber) {

                // 支払い情報を取得
                $paymentIntent = $this->getPaymentIntent($paymentIntentId);
                // 購入金額
                $buyAmount     = $paymentIntent->amount;
                // 決済番号
                $paymentNumber = $paymentIntent->id;
                // 支払い方法（カード・GooglePay・ApplePay）
                $paymentMethodId = $paymentIntent->payment_method;
                $paymentMethod   = $this->getPaymentMethodByPaymentMethodId($paymentMethodId);
                $paymentType     = $paymentMethod->type;
                // 有効期限
                $expirationDate = date('Y-m-d H:i:s', strtotime('+6 month'));

                // ポイント購入履歴を作成
                $this->pointRepository->createPointBuyHistory($userId, $buyAmount, $paymentType, $paymentNumber, true, $expirationDate);

                // ポイント変動履歴作成
                $this->pointFluctuationHistoryRepository->createPointFluctuationHistory($userId, EntityType::User, $buyAmount, 2);

                // 投げ銭登録
                $userTipId = $this->userTipRepository->createUserTip($staffId, $userId, $message, $buyAmount, $deskNumber, $guestNickname);

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
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * 応援履歴一覧取得
     *
     * @param int $userId
     * @return array $args
     */
    public function list(int $userId): array
    {
        $userTips = $this->userTipRepository->getAllByUserId($userId);

        $tipList = $userTips->map(function ($tip) {
            $staffProfileImage = $tip->staff->staffProfileImage;

            return [
                'tipId' => $tip->tip_id,
                'image' => $this->getFileUrl($staffProfileImage->file_name ?? null, $staffProfileImage->file_type ?? null),
                'createdAt' => Carbon::parse($tip->created_at)->format('Y-m-d'),
                'staffName' => $tip->staff->staff_name,
                'points' => $tip->tipping_amount,
                'isRead' => $tip->staffReply ? $tip->is_user_read : true,
            ];
        });

        $args = [
            'userTips' => $tipList
        ];

        return $args;
    }

    /**
     * 応援履歴取得
     * @param int $tipId
     * @return array $args
     */
    public function show(int $tipId): array
    {
        $userTipRepo = $this->userTipRepository->findByTipId($tipId);
        $userProfileImage = $userTipRepo->user->userProfileImage;

        $userTip = new UserTip(
            $userTipRepo->tip_id,
            $userTipRepo->user_id,
            $userTipRepo->staff_id,
            $this->getFileUrl($userProfileImage->file_name ?? null, $userProfileImage->file_type ?? null),
            Carbon::parse($userTipRepo->created_at)->format('Y-m-d'),
            $userTipRepo->guest_nickname ?? $userTipRepo->user->nickname,
            $userTipRepo->tipping_amount,
            $userTipRepo->message,
            false,
            false
        );

        $staffTipReply = null;
        if (!is_null($userTipRepo->staffReply)) {
            $staffProfileImage = $userTipRepo->staff->staffProfileImages->first();
            $staffTipReply = new StaffTip(
                $userTipRepo->staffReply->reply_id,
                $this->getFileUrl($staffProfileImage->file_name ?? null, $staffProfileImage->file_type ?? null),
                Carbon::parse($userTipRepo->staffReply->created_at)->format('Y-m-d'),
                $userTipRepo->staff->staff_name,
                $userTipRepo->staffReply->message,
                $this->getFileUrl($userTipRepo->staffReply->replyMedia->file_name ?? null, $userTipRepo->staffReply->replyMedia->file_type ?? null),
                $this->getFileType($userTipRepo->staffReply->replyMedia->file_type ?? null),
            );
            // スタッフ返信がある場合にユーザー既読をつける
            $this->userTipRepository->updateIsUserReadByTipId($tipId);
        }


        $args = [
            'staffName'     => $userTipRepo->staff->staff_name,
            'userTip'       => $userTip,
            'staffTipReply' => $staffTipReply
        ];

        return $args;
    }
}
