<?php

namespace App\Services;

use App\Enums\EntityType;
use App\Models\LoginHistory;
use App\Models\User;
use App\Repositories\Staff\StaffRepositoryInterface;
use App\Repositories\BankAccount\BankAccountRepositoryInterface;
use App\Repositories\TransferRequest\TransferRequestRepositoryInterface;
use App\Repositories\BusinessOperator\BusinessOperatorRepositoryInterface;
use App\Repositories\LoginHistory\LoginHistoryRepositoryInterface;
use App\Repositories\Notification\NotificationRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Token\TokenRepositoryInterface;
use App\Trais\StripeTrait;

class CancelService
{
    use StripeTrait;

    private StaffRepositoryInterface $staffRepositoryInterface;
    private BankAccountRepositoryInterface $bankAccountRepositoryInterface;
    private TransferRequestRepositoryInterface $transferRequestRepositoryInterface;
    private BusinessOperatorRepositoryInterface $businessOperatorRepositoryInterface;
    private LoginHistoryRepositoryInterface $loginHistoryRepositoryInterface;
    private NotificationRepositoryInterface $notificationRepositoryInterface;
    private UserRepositoryInterface $userRepositoryInterface;
    private TokenRepositoryInterface $tokenRepositoryInterface;

    public function __construct(
        StaffRepositoryInterface $staffRepositoryInterface,
        BankAccountRepositoryInterface $bankAccountRepositoryInterface,
        TransferRequestRepositoryInterface $transferRequestRepositoryInterface,
        BusinessOperatorRepositoryInterface $businessOperatorRepositoryInterface,
        LoginHistoryRepositoryInterface $loginHistoryRepositoryInterface,
        NotificationRepositoryInterface $notificationRepositoryInterface,
        UserRepositoryInterface $userRepositoryInterface,
        TokenRepositoryInterface $tokenRepositoryInterface
    ) {
        $this->staffRepositoryInterface = $staffRepositoryInterface;
        $this->bankAccountRepositoryInterface = $bankAccountRepositoryInterface;
        $this->transferRequestRepositoryInterface = $transferRequestRepositoryInterface;
        $this->businessOperatorRepositoryInterface = $businessOperatorRepositoryInterface;
        $this->loginHistoryRepositoryInterface = $loginHistoryRepositoryInterface;
        $this->notificationRepositoryInterface = $notificationRepositoryInterface;
        $this->userRepositoryInterface = $userRepositoryInterface;
        $this->tokenRepositoryInterface = $tokenRepositoryInterface;
    }

    /**
     * 事業者退会処理
     * @param int $businessId
     * @return void
     */
    public function deleteBusinessOperator(int $businessId): void
    {
        // スタッフリストを取得
        $staffList = $this->staffRepositoryInterface->getStaffByBusinessId($businessId);

        foreach ($staffList as $staff) {
            // ログイン履歴削除
            $this->loginHistoryRepositoryInterface->deleteHistoryByEntity(EntityType::Staff, $staff->staff_id);

            // お知らせ既読削除
            $this->notificationRepositoryInterface->deleteNotificationReadByEntity(EntityType::Staff, $staff->staff_id);

            // 個人向けお知らせ削除
            $this->notificationRepositoryInterface->deletePrivateNotificationByEntity(EntityType::Staff, $staff->staff_id);

            // お気に入りスタッフ削除
            $this->userRepositoryInterface->deleteFavoritesByStaffId($staff->staff_id);

            // いいね削除
            $this->userRepositoryInterface->deleteLikesByStaffId($staff->staff_id);

            // スタッフ通知削除
            $this->staffRepositoryInterface->deleteStaffNotificationByStaffId($staff->staff_id);

            // スタッフ画像をS3からファイル削除

            // スタッフ画像削除
            foreach ($staff->staffProfileImages as $image) {
                // TODO:S3からファイル削除

                // テーブルから削除
                $this->staffRepositoryInterface->deleteStaffProfileImageByStaffIdOrder($staff->staff_id, $image->order);
            }

            // スタッフスケジュール削除
            $this->staffRepositoryInterface->deleteStaffSchedulesByStaffId($staff->staff_id);

            // トークン
            $this->tokenRepositoryInterface->deleteByEntity(EntityType::Staff, $staff->staff_id);

            // スタッフ論理削除
            $this->staffRepositoryInterface->deleteStaff($staff->staff_id);
        }

        $businessOperator = $this->businessOperatorRepositoryInterface->findByBusinessId($businessId);

        // 事業者画像を削除
        foreach ($businessOperator->businessProfileImages as $image) {
            // TODO:S3からファイル削除

            // テーブルから削除
            $this->businessOperatorRepositoryInterface->deleteImageById($image->image_id);
        }

        // レビューを削除
        $this->businessOperatorRepositoryInterface->deleteReviewsByBusinessId($businessId);

        // 事業者設定を削除
        $this->businessOperatorRepositoryInterface->deleteSettingByBusinessId($businessId);

        // 投げ銭金額設定を削除
        $this->businessOperatorRepositoryInterface->deleteBusinessTippingAmountSettingByBusiness($businessId);

        // ログイン履歴を削除
        $this->loginHistoryRepositoryInterface->deleteHistoryByEntity(EntityType::BusinessOperator, $businessId);

        // お知らせ既読削除
        $this->notificationRepositoryInterface->deleteNotificationReadByEntity(EntityType::BusinessOperator, $businessId);

        // 個人向けお知らせ削除
        $this->notificationRepositoryInterface->deletePrivateNotificationByEntity(EntityType::BusinessOperator, $businessId);

        // 振込口座を削除
        $this->bankAccountRepositoryInterface->deleteBankAccountByEntity(EntityType::BusinessOperator, $businessId);

        // 振込申請の口座情報を更新(Null)
        $this->transferRequestRepositoryInterface->updateBankByEntity(EntityType::BusinessOperator, $businessId);

        // 事業者の論理削除
        $this->businessOperatorRepositoryInterface->logicalDeleteBusinessOperatorById($businessId);
    }

    public function deleteUser(User $user): void
    {
        $userId   = $user->user_id;
        $stripeId = $user->stripe_id;

        // ログイン履歴削除
        $this->loginHistoryRepositoryInterface->deleteHistoryByEntity(EntityType::User, $userId);

        // お知らせ既読削除
        $this->notificationRepositoryInterface->deleteNotificationReadByEntity(EntityType::User, $userId);

        // 個人向けお知らせ削除
        $this->notificationRepositoryInterface->deletePrivateNotificationByEntity(EntityType::User, $userId);

        // お気に入りスタッフ削除
        $this->userRepositoryInterface->deleteFavoritesByUserId($userId);

        // いいね削除
        $this->userRepositoryInterface->deleteLikesByUserId($userId);

        // 投げ銭ユーザー画像削除
        // TODO:S3からファイル削除

        // テーブルから削除
        $this->userRepositoryInterface->deleteUserProfileImageByUserId($userId);

        // レビュー削除
        $this->businessOperatorRepositoryInterface->deleteReviewsByUserId($userId);

        // 支払い情報があれば削除
        if ($this->getCustomer($stripeId)->default_source !== null) {
            $this->detachPaymentMethod($stripeId);
        }

        // 投げ銭ユーザーの論理削除
        $this->userRepositoryInterface->deleteUser($userId);
    }
}
