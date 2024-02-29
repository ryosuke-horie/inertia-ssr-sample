<?php

namespace App\Services;

use App\Domain\Notification\NotificationList;
use App\Enums\EntityType;
use App\Http\Requests\BusinessOperator\ProfileUpdateRequest;
use App\Models\BusinessOperator;
use App\Models\BusinessProfileImage;
use App\Models\BusinessReview;
use App\Repositories\BusinessOperator\BusinessOperatorRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Inertia\Response;
use App\Enums\ShiftStatus;
use Illuminate\Http\Request;
use App\Trais\FileTrait;
use App\Trais\TokenTrait;
use App\Repositories\Token\TokenRepositoryInterface;
use App\Trais\EmailTrait;
use Error;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\ChangeBusinessEmailMail;

class BusinessOperatorService
{
    use FileTrait;
    use TokenTrait;
    use EmailTrait;

    private BusinessOperatorRepositoryInterface $businessOperatorRepository;
    private TokenRepositoryInterface $tokenRepositoryInterface;

    public function __construct(BusinessOperatorRepositoryInterface $businessOperatorRepository, TokenRepositoryInterface $tokenRepositoryInterface)
    {
        $this->tokenRepositoryInterface = $tokenRepositoryInterface;
        $this->businessOperatorRepository = $businessOperatorRepository;
    }

    /**
     * 事業者情報取得
     *
     * @param int $businessId
     * @return BusinessOperator
     */
    public function findByBusinessId(int $businessId): BusinessOperator
    {
        return $this->businessOperatorRepository->findByBusinessId($businessId);
    }

    /**
     * 事業者プロフィールデータ取得
     *
     * @param int $businessId
     * @return array $args
     */
    public function profile(int $businessId): array
    {
        // 事業者取得
        $businessOperator = BusinessOperator::find($businessId);

        // 親法人が存在する場合、法人名を取得する
        $corporationName = $businessOperator->corporation_name;
        if (!is_null($businessOperator->corporation_id)) {
            $corporationName = $businessOperator->corporation->corporation_name;
        }

        // 事業者画像取得
        $businessProfileImages =  BusinessProfileImage::where('business_id', $businessId)->get();

        $newProfileImages = $businessProfileImages->map(function ($businessProfileImage) {
            return [
                'image' => $this->getFileUrl($businessProfileImage->file_name, $businessProfileImage->file_type),
                'order' => $businessProfileImage->order,
            ];
        });

        $args = [
            'businessName'        => $businessOperator->business_name,
            'corporationName'     => $corporationName,
            'businessDescription' => $businessOperator->business_description,
            'email'               => $businessOperator->email,
            'images'              => $newProfileImages
        ];

        return $args;
    }

    /**
     * プロフィール画像追加・更新
     * @param int $businessId
     * @param Request $request
     * @return array $args
     */
    public function updateProfileImage(int $businessId, Request $request): array
    {
        $order = (int)$request->input('order');

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');

            $requestFile = $image->getClientOriginalName();
            $path        = $image->store('public/images/test');
            $fileName    = pathinfo($path, PATHINFO_FILENAME);
            $fileType    = pathinfo($requestFile, PATHINFO_EXTENSION);
            $fileSize    = $image->getSize();

            if ($this->checkImageFileType($fileType)) {
                $businessProfileImage = $this->businessOperatorRepository->findBusinessProfileImageByBusinessIdOrder($businessId, $order);
                if (is_null($businessProfileImage)) {
                    $this->businessOperatorRepository->createBusinessProfileImage($businessId, $order, $fileName, $fileType, $fileSize);
                } else {
                    $this->deleteFile($businessProfileImage->file_name, $businessProfileImage->file_type);
                    $this->businessOperatorRepository->updateBusinessProfileImage($businessId, $order, $fileName, $fileType, $fileSize);
                }
            }
        }

        return $this->profile($businessId);
    }

    /**
     * プロフィール画像削除
     * @param int $businessId
     * @param Request $request
     * @return array $args
     */
    public function deleteProfileImage(int $businessId, Request $request): array
    {
        $order = (int)$request->input('order');

        $businessProfileImage = $this->businessOperatorRepository->findBusinessProfileImageByBusinessIdOrder($businessId, $order);

        if (!is_null($businessProfileImage)) {
            $this->deleteFile($businessProfileImage->file_name, $businessProfileImage->file_type);
            $this->businessOperatorRepository->deleteBusinessProfileImageByBusinessIdOrder($businessId, $order);
        }

        return $this->profile($businessId);
    }

    /**
     * プロフィール更新処理
     * @param int $businessId
     * @param ProfileUpdateRequest $request
     */
    public function updateProfile(int $businessId, ProfileUpdateRequest $request)
    {

        $businessOperator = BusinessOperator::find($businessId);

        $businessOperator->business_description = $request->business_description;
        $businessOperator->save();

        return;
    }

    /**
     * メールアドレス変更用トークン作成＆メール送信
     * @param int $businessId
     * @param string $businessName
     * @param string $email
     * @return void
     */
    public function registerTokenForEmail(int $businessId, string $businessName, string $email): void
    {
        $token = $this->generateToken();
        $this->tokenRepositoryInterface->registerToken($businessId, EntityType::BusinessOperator, $email, $token);
        Mail::to($email)->send(new ChangeBusinessEmailMail($businessName, $email, $token));
    }

    /**
     * メールアドレス変更
     * @param string $token
     * @param string $email
     * @return void
     */
    public function updateEmail(string $token, string $email): void
    {
        $businessId = $this->tokenRepositoryInterface->getBusinessIdByTokenAndEmail($token, $email);

        if (is_null($businessId)) {
            throw new Exception('トークン管理に紐づくレコードがありませんでした。');
        }

        $emailHash = hash('sha256', $this->convertToLowercase($email));

        if (!is_null($this->businessOperatorRepository->findByEmailHash($emailHash))) {
            throw new Exception();
        }

        $this->businessOperatorRepository->updateEmail($businessId, $email, $emailHash);
        $this->tokenRepositoryInterface->deleteToken($token);
    }

    /**
     * パスワード更新
     * @param int $businessId
     * @param string $password
     * @return void
     */
    public function updatePassword(int $businessId, string $password): void
    {
        $this->businessOperatorRepository->updatePassword($businessId, $password);
    }
}
