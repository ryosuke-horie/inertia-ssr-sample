<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\BusinessOperator\BusinessOperatorRepositoryInterface;
use App\Repositories\Staff\StaffRepositoryInterface;
use App\Models\BusinessReview;
use App\Trais\FileTrait;

class GuestUserService
{
    use FileTrait;

    protected $userRepository;
    protected $businessOperatorRepository;
    protected $staffRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        BusinessOperatorRepositoryInterface $businessOperatorRepository,
        StaffRepositoryInterface $staffRepository
    ) {
        $this->userRepository = $userRepository;
        $this->businessOperatorRepository = $businessOperatorRepository;
        $this->staffRepository = $staffRepository;
    }

    /**
     * マイページ情報取得
     *
     * @param int $userId
     * @return array
     */
    public function getMypage(int $userId): array
    {
        $userProfile = $this->userRepository->getUserProfileImageByUserId($userId);
        $userProfileImage = $this->getFileUrl($userProfile->userProfileImage->file_name ?? null, $userProfile->userProfileImage->file_type ?? null);

        $args = [
            'userId'   => $userProfile->user_id,
            'nickname' => $userProfile->nickname,
            'userProfileImage' => $userProfileImage,
        ];

        return $args;
    }

    /**
     * ユーザー情報取得
     *
     * @param int $userId
     * @return array
     */
    public function getUserProfileImage(int $userId): array
    {
        $userProfile = $this->userRepository->getUserProfileImageByUserId($userId);
        $userProfileImage = $this->getFileUrl($userProfile->userProfileImage->file_name ?? null, $userProfile->userProfileImage->file_type ?? null);

        $args = [
            'userId'   => $userProfile->user_id,
            'nickname' => $userProfile->nickname,
            'birthdate' => $userProfile->birthdate,
            'isShowRanking' => $userProfile->is_show_ranking,
            'userProfileImage' => $userProfileImage,
        ];

        return $args;
    }

    /**
     * 事業者情報一覧を取得
     *
     * @return array
     */
    public function getBusinessOperatorList(): array
    {
        $businessOperators = $this->businessOperatorRepository->getAllBusinessOperatorsWithImages();

        $businessList = $businessOperators->map(function ($businessOperator) {
            $businessOperatorImages = $businessOperator->businessProfileImages->map(function ($image) {
                return [
                    'image' => $this->getFileUrl($image->file_name, $image->file_type),
                    'order' => $image->order
                ];
            });

            return [
                'id'     => $businessOperator->business_id,
                'name'   => $businessOperator->business_name,
                'images' => $businessOperatorImages->toArray(),
                'city'   => $businessOperator->city,
                'href'   => route('guest.business-operator.show', ['businessId' => $businessOperator->business_id]),
            ];
        });

        $args = [
            'businessList' => $businessList->toArray()
        ];

        return $args;
    }

    /**
     * 事業者詳細情報を取得
     *
     * @param int $businessId 事業者のID
     * @return array $args
     */
    public function getBusinessOperatorShow(int $businessId): array
    {
        // 事業者プロフィール取得
        $businessOperator = $this->businessOperatorRepository->findByBusinessId($businessId);
        $businessOperatorImages = $businessOperator->businessProfileImages->map(function ($image) {
            return [
                'image' => $this->getFileUrl($image->file_name, $image->file_type),
                'order' => $image->order
            ];
        });

        $businessOperatorProfile = [
            'businessName'        => $businessOperator->business_name,
            'businessDescription' => $businessOperator->business_description,
            'city'                => $businessOperator->city,
            'prefCode'            => $businessOperator->pref_code,
            'businessForm'        => $businessOperator->business_form,
            'images'              => $businessOperatorImages->toArray()
        ];

        // スタッフ情報取得
        $staffList = $this->businessOperatorRepository->findBusinessOperatorWithStaff($businessId)->staff;

        $staffData = $staffList->map(function ($staff) {
            $staffImages = $staff->staffProfileImages->map(function ($image) {
                return [
                    'image' => $this->getFileUrl($image->file_name, $image->file_type),
                    'order' => $image->order
                ];
            });

            return [
                'staffId'    => $staff->staff_id,
                'businessId' => $staff->business_id,
                'staffName'  => $staff->staff_name,
                'staffProfileImages' => $staffImages->toArray()
            ];
        });

        // 口コミ取得
        $businessReviews = $this->businessOperatorRepository->getBusinessReviews($businessId);
        $reviews = $businessReviews->flatMap(function ($businessOperator) {
            return $businessOperator->businessReviews->sortByDesc('created_at')->map(function ($review) {
                return [
                    'reviewId'         => $review->review_id,
                    'userId'           => $review->user_id,
                    'nickname'         => $review->user->nickname,
                    'comment'          => $review->comment,
                    'createdAt'        => $review->created_at->format('Y-m-d'),
                    'createdAgo'       => $review->created_at->diffForHumans(),
                    'userProfileImage' => $this->getFileUrl($review->user->userProfileImage->file_name ?? null, $review->user->userProfileImage->file_type ?? null),
                ];
            });
        });

        $args = [
            'businessOperator' => $businessOperatorProfile,
            'staff'            => $staffData->toArray(),
            'businessReviews'  => $reviews->toArray(),
        ];

        return $args;
    }

    /**
     * 事業者内のスタッフ詳細一覧を加工して取得
     *
     * @param int $businessId 事業者ID
     * @return array $args
     */
    public function getBusinessOperatorShowWithStaffDetail(int $businessId): array
    {
        $businessOperator = $this->businessOperatorRepository->findBusinessOperatorWithStaffDetailForGuest($businessId);
        $staffData = $businessOperator->staff;

        $staffData = $staffData->map(function ($staff) {
            $staffProfileImages = $staff->staffProfileImages->map(function ($image) {
                return [
                    'image' => $this->getFileUrl($image->file_name, $image->file_type),
                    'order' => $image->order
                ];
            });

            $todayShiftStatus = $staff->staffSchedules->pluck('shift_status')->first();

            return [
                'staffId' => $staff->staff_id,
                'staffName' => $staff->staff_name,
                'todayShiftStatus' => (int) $todayShiftStatus, // 1:出勤、2:休み、3:未定
                'images' => $staffProfileImages,
            ];
        });

        $args = [
            'staffList' => $staffData->toArray(),
            'businessId' => $businessId,
        ];

        return $args;
    }

    /**
     * 事業者口コミ登録
     *
     * @param int $businessId
     * @param int $userId
     * @param string $comment
     * @return BusinessReview
     */
    public function storeReview(int $businessId, int $userId, string $comment): BusinessReview
    {
        return $this->businessOperatorRepository->storeReviw($businessId, $userId, $comment);
    }

    /**
     * スタッフの詳細情報を取得
     *
     * @param int $userId ユーザーID
     * @param int $businessId 事業者ID
     * @param int $staffId スタッフID
     * @return array $args
     */
    public function getStaffDetail(int $userId, int $businessId, int $staffId): array
    {
        $staff = $this->staffRepository->findByStaffId($staffId);
        $images = $staff->staffProfileImages->map(function ($image) {
            return [
                'image' => $this->getFileUrl($image->file_name, $image->file_type),
                'order' => $image->order
            ];
        });

        $businessOperator = $this->businessOperatorRepository->findByBusinessId($businessId);
        $businessSettings = $this->businessOperatorRepository->getBusinessTippingAmountSettingByBusinessId($businessId)->map(function ($item) {
            return $item->tippingAmountSetting->only(['amount']);
        });
        $userLikeCount    = $this->staffRepository->getUserLikesCountByStaffId($staffId);

        $args = [
            'staffId'          => $staffId,
            'staffName'        => $staff->staff_name,
            'comment'          => $staff->comment,
            'images'           => $images,
            'businessId'       => $businessId,
            'businessName'     => $businessOperator->business_name,
            'businessSettings' => $businessSettings,
            'userLikeCount'    => $userLikeCount,
        ];

        return $args;
    }

    public function getGuestStripeId(): string
    {
        return $this->userRepository->getGuestStripeId();
    }
}
