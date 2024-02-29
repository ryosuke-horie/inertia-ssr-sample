<?php

namespace App\Repositories\BusinessOperator;

use App\Models\BusinessApplication;
use App\Models\BusinessOperator;
use App\Models\BusinessProfileImage;
use App\Models\BusinessReview;
use App\Models\BusinessSetting;
use App\Models\BusinessTippingAmountSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BusinessOperatorRepositoryInterface
{
    /**
     * 事業者情報取得
     *
     * @param int $businessId
     * @return BusinessOperator
     */
    public function findByBusinessId(int $businessId): ?BusinessOperator;

    /**
     * 事業者とそのプロファイル画像を含む情報の一覧を取得
     *
     * @return Collection
     */
    public function getAllBusinessOperatorsWithImages(): Collection;

    /**
     * スタッフ情報一覧を取得
     *
     * @param int $businessId
     * @return BusinessOperator|null
     */
    public function findBusinessOperatorWithStaff(int $businessId): ?BusinessOperator;

    /**
     * 会員ユーザー用事業者内のスタッフ詳細一覧を取得
     *
     * 事業者情報、スタッフ情報、お気に入りスタッフ、スケジュール
     * 事業者内ランキング用にポイント降順、名前昇順で取得
     *
     * @param int $businessId
     * @return BusinessOperator|null
     */
    public function findBusinessOperatorWithStaffDetail(int $businessId): ?BusinessOperator;

    /**
     * ゲストユーザー用事業者内のスタッフ詳細一覧を取得
     *
     * ゲストはお気に入り機能が使えないため、お気に入りスタッフ情報は取得しない
     * 事業者情報、スタッフ情報、スケジュール
     * 事業者内ランキング用にポイント降順、名前昇順で取得
     *
     * @param int $businessId
     * @return BusinessOperator|null
     */
    public function findBusinessOperatorWithStaffDetailForGuest(int $businessId): ?BusinessOperator;

    /**
     * 口コミ取得
     *
     * @param int $reviewId
     * @return BusinessReview
     */
    public function getReview(int $reviewId): BusinessReview;
    /**
     * 事業者口コミを取得
     *
     * @param int $businessId
     * @return Collection
     */
    public function getBusinessReviews(int $businessId): Collection;

    /**
     * 事業者口コミを取得
     *
     * @param int $businessId
     * @return BusinessOperator
     */
    public function getAllBusinessReviews(int $businessId): BusinessOperator;

    /**
     * 口コミ登録
     *
     * @param int $businessId
     * @param int $userId
     * @param string $comment
     * @return BusinessReview
     */
    public function storeReviw(int $businessId, int $userId, string $comment): BusinessReview;

    /**
     * 口コミ削除
     *
     * @param int $businessId
     * @param int $reviewId
     * @param int $userId
     * @return bool
     */
    public function deleteReview(int $businessId, int $reviewId, int $userId): bool;

    /**
     * 設定情報を取得
     * @param int $businessId
     * @return BusinessSetting
     */
    public function getBusinessSettingByBusinessId(int $businessId): BusinessSetting;

    /**
     * 設定情報更新
     * @param int $settingId
     * @param array $param
     */
    public function updateBusinessSettingBySettingId(int $settingId, array $param): void;

    /**
     * 設定情報登録
     * @param array $param
     * @return void
     */
    public function createBusinessSetting(array $param): void;

    /**
     * 投げ銭金額設定を取得
     * @param int $businessId
     * @param int $settingId
     * @return Collection
     */
    public function getBusinessTippingAmountSettingByBusinessSetting(int $businessId, int $settingId): Collection;

    /**
     * 投げ銭金額設定を削除
     * @param int $businessId
     */
    public function deleteBusinessTippingAmountSettingByBusiness(int $businessId): void;

    /**
     * 投げ銭金額設定を登録
     * @param int $businessId
     */
    public function createBusinessTippingAmountSetting(int $businessId, int $settingId): void;

    /**
     * 口コミを日付順で取得
     * @param int $businessId
     */
    public function getReviewBybusinessOrderByDate(int $businessId): Collection;

    /**
     * 口コミをID指定で削除
     * @param int $reviewId
     */
    public function deleteReviewById(int $reviewId): void;

    /**
     * 対象事業者を論理削除
     * @param int $businessId
     */
    public function logicalDeleteBusinessOperatorById(int $businessId): void;

    /**
     * 事業者画像を削除
     * @param int $imageId
     */
    public function deleteImageById(int $imageId): void;

    /**
     * 対象事業者の口コミを削除
     * @param int $businessId
     */
    public function deleteReviewsByBusinessId(int $businessId): void;

    /**
     * 対象投げ銭ユーザーの口コミを削除
     * @param int $userId
     */
    public function deleteReviewsByUserId(int $userId): void;

    /**
     * 対象事業者の設定を削除
     * @param int $businessId
     */
    public function deleteSettingByBusinessId(int $businessId): void;

    /**
     * 投げ銭設定情報を一括取得
     * @param int $businessId
     * @return Collection
     */
    public function getBusinessTippingAmountSettingByBusinessId(int $businessId): Collection;

    /**
     * ページネーション付き事業者情報詳細取得
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginateBusiness(): LengthAwarePaginator;

    /**
     * 事業者情報詳細取得
     *
     * @return BusinessOperator
     */
    public function getBusinessDetailsByBusinessId(int $businessId): BusinessOperator;

    /**
     * 検索条件に基づいて事業者を取得する
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function searchBusinessOperatorByRequest(Request $request): LengthAwarePaginator;

    /**
     * 事業者登録
     *
     * @param array $request
     * @return BusinessOperator
     */
    public function createBusinessByRequest(array $request): BusinessOperator;


    /**
     * 事業者更新
     *
     * @param array $request
     * @return void
     */
    public function updateBusinessByRequest(array $request): void;

    /**
     * 事業者申請登録
     *
     * @param array $request
     * @return BusinessApplication
     */
    public function createBusinessApplicationByRequest(array $request): BusinessApplication;

    /**
     * 事業者申請更新
     *
     * @param array $request
     * @return void
     */
    public function updateBusinessApplicationByRequest(array $request): void;

    /**
     * 事業者申込申請情報取得
     *
     * @param int $applicationId
     * @return BusinessApplication
     */
    public function getBusinessApplicationByApplicationId(int $applicationId): BusinessApplication;

    /*
     * 事業者画像取得
     *
     * @param int $businessId
     * @param int $order
     * @return ?BusinessProfileImage
     */
    public function findBusinessProfileImageByBusinessIdOrder(int $businessId, int $order): ?BusinessProfileImage;

    /**
     * 事業者画像追加
     * @param int $businessId
     * @param int $order
     * @param string $fileName
     * @param string $fileType
     * @param int $fileSize
     * @return BusinessProfileImage
     */
    public function createBusinessProfileImage(int $businessId, int $order, string $fileName, string $fileType, int $fileSize): BusinessProfileImage;

    /**
     * 事業者画像更新
     * @param int $businessId
     * @param int $order
     * @param string $fileName
     * @param string $fileType
     * @param int $fileSize
     * @return BusinessProfileImage
     */
    public function updateBusinessProfileImage(int $businessId, int $order, string $fileName, string $fileType, int $fileSize): BusinessProfileImage;

    /**
     * 事業者画像削除
     *
     * @param int $businessId
     * @param int $order
     * @return void
     */
    public function deleteBusinessProfileImageByBusinessIdOrder(int $businessId, int $order): void;

    /**
     * メールアドレスで事業者取得
     * @param string $emailHash
     */
    public function findByEmailHash(string $emailHash): ?BusinessOperator;

    /**
     * メールアドレス変更
     * @param int $businessId
     * @param string $email
     * @param string $emailHash
     * @return void
     */
    public function updateEmail(int $businessId, string $email, string $emailHash): void;

    /**
     * パスワード変更
     * @param int $businessId
     * @param string $password
     * @return void
     */
    public function updatePassword(int $businessId, string $password): void;

    /**
     * 対象法人配下の事業者リストを取得
     * @param int $corporationId
     * @return Collection
     */
    public function getBusinessByCorporationId(int $corporationId): Collection;

    /**
     * 対象法人の事業者申込データを取得
     * @param int $corporationId
     * @return Collection
     */
    public function getBusinessApplicationByCorporationId(int $corporationId): Collection;

    /**
     * 事業者申請IDでデータ取得
     * @param int $businessApplicationId
     * @return BusinessApplication
     */
    public function findBusinessApplicationByBusinessApplicationId(int $businessApplicationId): BusinessApplication;

    /**
     * 対象法人配下に非公開の事業者が存在するかチェック
     * @param int $corporationId
     * @return bool
     */
    public function existsPublishByCorporationId(int $corporationId): bool;
}
