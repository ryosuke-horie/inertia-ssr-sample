<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\UserProfileImage;
use App\Models\StaffFavorite;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    /**
     * ゲスト一覧取得
     * @return Collection
     */
    public function getAllUser(): Collection;


    /**
     * ゲストのStripeID取得
     * @return string
     */
    public function getGuestStripeId(): string;

    /**
     * ユーザー取得
     * @param int $userId
     * @return User
     */
    public function findByUserId(int $userId): User;

    /**
     * ユーザー取得
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User;

    /**
     * メールアドレス変更
     * @param int $userId
     * @param string $email
     * @return User
     */
    public function updateEmail(int $userId, string $email): User;

    /**
     * パスワード変更
     * @param int $userId
     * @param string $password
     * @return void
     */
    public function updatePassword(int $userId, string $password): void;

    /**
     * パスワード変更
     * @param int $userId
     * @param string $stripeId
     * @return void
     */
    public function updateStripeId(int $userId, string $stripeId): void;

    /**
     * ユーザーのプロフィール画像を取得
     * ユーザー情報、ユーザープロフィール画像
     *
     * @param int $userId
     * @return User
     */
    public function getUserProfileImageByUserId(int $userId): User;

    /**
     * ユーザープロフィール更新
     * @param int $userId
     * @param string $nickname
     * @return User
     */
    public function updateUserProfileByUserId(int $userId, string $nickname): User;


    /**
     * スタッフ画像取得
     *
     * @param int $userId
     * @return ?UserProfileImage
     */
    public function findUserProfileImageByUserId(int $userId): ?UserProfileImage;

    /**
     * スタッフ画像追加
     * @param int $userId
     * @param string $fileName
     * @param string $fileType
     * @param int $fileSize
     * @return UserProfileImage
     */
    public function createUserProfileImage(int $userId, string $fileName, string $fileType, int $fileSize): UserProfileImage;

    /**
     * スタッフ画像更新
     * @param int $userId
     * @param string $fileName
     * @param string $fileType
     * @param int $fileSize
     * @return UserProfileImage
     */
    public function updateUserProfileImage(int $userId, string $fileName, string $fileType, int $fileSize): UserProfileImage;


    /**
     * ユーザーのお気に入りスタッフ情報+スタッフスケジュールを取得
     *
     * @param int $userId
     * @return User|null
     */
    public function getStaffFavoritesOrSchedulesByUserId(int $userId): ?User;

    /**
     * 会員ユーザーのマイページ情報を取得
     *
     * @param int $userId
     * @return User
     */
    public function getUserMypageInfoByUserId(int $userId): User;

    /**
     * お気に入りスタッフ情報取得
     *
     * @param int $userId
     * @param int $staffId
     * @return StaffFavorite|null
     */
    public function findUserWithFavoriteStaff(int $userId, int $staffId): ?StaffFavorite;

    /**
    * お気に入りスタッフ削除
    *
    * @param int $favoriteId
    * @return null
    */
    public function deleteFavorite(int $favoriteId): null;

    /**
     * お気に入りスタッフ追加
     *
     * @param int $userId
     * @param int $staffId
     * @return int
     */
    public function createFavorite(int $userId, int $staffId): int;

    /**
     * いいね削除
     *
     * @param int $likeId
     * @return null
     */
    public function deleteUserLike(int $likeId): null;

    /**
     * いいね追加
     *
     * @param int $userId
     * @param int $staffId
     * @return int
     */
    public function createUserLike(int $userId, int $staffId): int;

    /**
     * 保有ポイントを更新
     *
     * @param int $userid
     * @param int $freePoints
     * @param int $paidPoints
     * @param int $amount
     * @return void
     */
    public function updateUserPoints(int $userid, int $freePoints, int $paidPoints, int $amount): void;

    /**
     * 投げ銭ユーザー論理削除
     * @param int $userId
     * @return void
     */
    public function deleteUser(int $userId): void;

    /**
     * 対象スタッフのお気に入りスタッフを削除
     * @param int $staffId
     * @return void
     */
    public function deleteFavoritesByStaffId(int $staffId): void;

    /**
     * 対象投げ銭ユーザーのお気に入りスタッフを削除
     * @param int $userId
     * @return void
     */
    public function deleteFavoritesByUserId(int $userId): void;

    /**
     * 対象スタッフのいいねを削除
     * @param int $staffId
     * @return void
     */
    public function deleteLikesByStaffId(int $staffId): void;

    /**
     * 対象投げ銭ユーザーのいいねを削除
     * @param int $userId
     * @return void
     */
    public function deleteLikesByuserId(int $userId): void;

    /**
     * 対象投げ銭ユーザーのプロフィール画像を削除
     * @param int $userId
     * @return void
     */
    public function deleteUserProfileImageByUserId(int $userId): void;



    /**
     * 指定された顧客IDに対応する支払い方法を取得
     *
     * @param string $stripeId Stripeの顧客ID
     * @return array Stripeの支払い方法の配列
     */
    public function getPaymentMethods(string $stripeId): array;


    /**
     * StripeIDを取得
     *
     * @param int $userId
     * @return string Stripeの顧客ID
     */
    public function getStripeId(int $userId): string;
}
