<?php

namespace App\Services;

use App\Enums\EntityType;
use App\Mail\ChangeUserEmailMail;
use App\Models\BusinessReview;
use App\Repositories\BusinessOperator\BusinessOperatorRepositoryInterface;
use App\Repositories\Staff\StaffRepositoryInterface;
use App\Repositories\Token\TokenRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Trais\EmailTrait;
use App\Trais\FileTrait;
use App\Trais\StripeTrait;
use App\Trais\TokenTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserService
{
    use EmailTrait;
    use FileTrait;
    use TokenTrait;
    use StripeTrait;

    protected $userRepository;
    protected $staffRepository;
    protected $businessOperatorRepository;
    protected $tokenRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        StaffRepositoryInterface $staffRepository,
        BusinessOperatorRepositoryInterface $businessOperatorRepository,
        TokenRepositoryInterface $tokenRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->staffRepository = $staffRepository;
        $this->businessOperatorRepository = $businessOperatorRepository;
        $this->tokenRepository = $tokenRepository;
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

    public function updateUserProfile(int $userId, string $nickname): void
    {
        $user = $this->userRepository->updateUserProfileByUserId($userId, $nickname);
        $this->updateCustomer($user->stripe_id, ['name' => $user->nickname]);
    }

    /**
     * メールアドレス変更用トークン作成＆メール送信
     * @param int $userId
     * @param string $name
     * @param string $email
     * @return void
     */
    public function registerTokenForEmail(int $userId, string $name, string $email): void
    {
        $token = $this->generateToken();
        $this->tokenRepository->registerToken($userId, EntityType::User, $email, $token);
        Mail::to($email)->send(new ChangeUserEmailMail($name, $email, $token));
    }

    /**
     * メールアドレス変更
     * @param string $token
     * @param string $email
     * @return void
     */
    public function updateEmail(string $token, string $email): void
    {
        $userId = $this->tokenRepository->getUserIdByTokenAndEmail($token, $email);

        if (is_null($userId)) {
            throw new Exception('トークン管理に紐づくレコードがありませんでした。');
        }

        $convertEmail = $this->convertToLowercase($email);

        if (!is_null($this->userRepository->findByEmail($convertEmail))) {
            throw new Exception('既に使用されているメールアドレスです。');
        }

        $this->tokenRepository->deleteToken($token);
        $user = $this->userRepository->updateEmail($userId, $email);
        $this->updateCustomer($user->stripe_id, ['email' => $email]);
    }

    /**
     * パスワード更新
     */
    public function updatePassword(int $userId, string $password): void
    {
        $this->userRepository->updatePassword($userId, $password);
    }

    /**
     * プロフィール画像追加・更新
     * @param int $userId
     * @param Request $request
     * @return array $args
     */
    public function updateUserProfileImage(int $userId, Request $request): array
    {
        // リクエストに画像が含まれていない場合は、現在の画像を返却
        if (!$request->hasFile('image') || !$request->file('image')->isValid()) {
            return $this->getUserProfileImage($userId);
        }

        $image = $request->file('image');
        $requestFile = $image->getClientOriginalName();
        $fileType    = pathinfo($requestFile, PATHINFO_EXTENSION);

        // 画像ファイル以外の場合処理を実行せずに現在の画像を返却
        if (!$this->checkImageFileType($fileType)) {
            return $this->getUserProfileImage($userId);
        }

        // 画像をS3にアップロード
        $s3FileName = Storage::disk('s3')->put('/user', $image, 'public');

        $fileName = '/test/' . $s3FileName;
        $fileSize    = $image->getSize();
        $userProfileImage = $this->userRepository->findUserProfileImageByuserId($userId);

        if (is_null($userProfileImage)) {
            $this->userRepository->createUserProfileImage($userId, $fileName, $fileType, $fileSize);
        } else {
            $this->deleteFile($userProfileImage->file_name, $userProfileImage->file_type);
            $this->userRepository->updateUserProfileImage($userId, $fileName, $fileType, $fileSize);
        }

        return $this->getUserProfileImage($userId);
    }

    /**
     * マイページ情報取得
     *
     * @param int $userId
     * @return array
     */
    public function getMypage(int $userId): array
    {
        $userProfile = $this->userRepository->getUserMypageInfoByUserId($userId);
        $userProfileImage = $this->getFileUrl($userProfile->userProfileImage->file_name ?? null, $userProfile->userProfileImage->file_type ?? null);

        $favoriteStaff = $userProfile->staffFavorites->map(function ($staffFavorite) {
            $staff = $staffFavorite->staff;
            $staffProfileImages = $staff->staffProfileImages->map(function ($image) {
                return [
                    'image' => $this->getFileUrl($image->file_name, $image->file_type),
                    'order' => $image->order
                ];
            });

            return [
                'staffId'    => $staff->staff_id,
                'businessId' => $staff->business_id,
                'staffName'  => $staff->staff_name,
                'staffProfileImages' =>  $staffProfileImages->toArray(),
                'businessName' => $staff->businessOperator->business_name,
            ];
        });

        $args = [
            'userId'   => $userProfile->user_id,
            'nickname' => $userProfile->nickname,
            'userProfileImage' => $userProfileImage,
            'favoriteStaff'    => $favoriteStaff->toArray(),
        ];

        return $args;
    }


    /**
     * 投げ銭ユーザーお気に入り付きスタッフ一覧取得
     *
     * @param int $userId
     * @return array
     */
    public function getFavoriteStaffList(int $userId): array
    {
        $user = $this->userRepository->getStaffFavoritesOrSchedulesByUserId($userId);

        $staffFavorites = $user->staffFavorites->map(function ($staffFavorite) {
            $staff = $staffFavorite->staff;
            $staffProfileImages = $staff->staffProfileImages->map(function ($image) {
                return [
                    'image' => $this->getFileUrl($image->file_name, $image->file_type),
                    'order' => $image->order
                ];
            });

            // staffFavoritesリレーションから直接お気に入りIDを取得
            $favoriteId = $staff->staffFavorites->first()->favorite_id ?? null;

            $todayShiftStatus = $staff->staffSchedules->pluck('shift_status')->first();
            return [
                'staffId' => $staff->staff_id,
                'businessId' => $staff->business_id,
                'favoriteId' => $favoriteId,
                'staffName' => $staff->staff_name,
                'staffProfileImages' =>  $staffProfileImages->toArray(),
                'todayShiftStatus' => (int) $todayShiftStatus,
                'businessName' => $staff->businessOperator->business_name,
            ];
        });

        $args = [
            'staffList' => $staffFavorites->toArray()
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
                'href'   => route('user.business-operator.show', ['businessId' => $businessOperator->business_id]),
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
        $businessOperator = $this->businessOperatorRepository->findBusinessOperatorWithStaffDetail($businessId);
        $staffData = $businessOperator->staff;

        $staffData = $staffData->map(function ($staff) {
            $staffProfileImages = $staff->staffProfileImages->map(function ($image) {
                return [
                    'image' => $this->getFileUrl($image->file_name, $image->file_type),
                    'order' => $image->order
                ];
            });

            // staffFavoritesリレーションから直接お気に入りIDを取得
            $favoriteId = $staff->staffFavorites->first()->favorite_id ?? null;

            $todayShiftStatus = $staff->staffSchedules->pluck('shift_status')->first();

            return [
                'staffId' => $staff->staff_id,
                'favoriteId' => $favoriteId,
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
     * 事業者口コミ削除
     *
     * @param int $businessId
     * @param int $reviewId
     * @param int $userId
     * @return bool
     */
    public function deleteReview(int $businessId, int $reviewId, int $userId): bool
    {
        return $this->businessOperatorRepository->deleteReview($businessId, $reviewId, $userId);
    }

    /**
     * お気に入りスタッフ切り替え
     *
     * @param int $userId
     * @param int $staffId
     * @param int|null $favoriteId
     * @return ?int
     */
    public function toggleFavorite(int $userId, int $staffId, ?int $favoriteId): ?int
    {
        if ($favoriteId) {
            return $this->userRepository->deleteFavorite($favoriteId);
        }

        return $this->userRepository->createFavorite($userId, $staffId);
    }

    /**
     * いいね切り替え
     *
     * @param int $userId
     * @param int $staffId
     * @param int|null $likeId
     * @return ?int
     */
    public function toggleUserLike(int $userId, int $staffId, ?int $likeId): ?int
    {
        if ($likeId) {
            return $this->userRepository->deleteUserLike($likeId);
        }

        return $this->userRepository->createUserLike($userId, $staffId);
    }

    /**
     * スタッフの詳細情報を取得
     *
     * @param int $userId ユーザーID
     * @param int $businessId 事業者ID
     * @param int $staffId スタッフID
     * @param int $freePoint 無償ポイントの数
     * @param int $paidPoint 有償ポイントの数
     * @param int $aiCount AIによるカウント数
     * @return array $args
     */
    public function getStaffDetail(int $userId, int $businessId, int $staffId, int $freePoint, int $paidPoint, int $aiCount): array
    {
        $totalPoints = $freePoint + $paidPoint;

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
        $favoriteStaff    = $this->userRepository->findUserWithFavoriteStaff($userId, $staffId);
        $userLikeCount    = $this->staffRepository->getUserLikesCountByStaffId($staffId);
        $userLike         = $this->staffRepository->findUserLikeByUserIdAndStaffId($userId, $staffId);

        $args = [
            'aiCount'          => $aiCount,
            'totalPoints'      => $totalPoints,
            'staffId'          => $staffId,
            'staffName'        => $staff->staff_name,
            'comment'          => $staff->comment,
            'images'           => $images,
            'businessId'       => $businessId,
            'businessName'     => $businessOperator->business_name,
            'businessSettings' => $businessSettings,
            'favoriteId'       => $favoriteStaff->favorite_id ?? null,
            'userLikeCount'    => $userLikeCount,
            'userLikeId'       => $userLike->like_id ?? null,
        ];

        return $args;
    }
}
