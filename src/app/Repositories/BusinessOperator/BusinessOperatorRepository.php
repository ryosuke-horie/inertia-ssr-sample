<?php

namespace App\Repositories\BusinessOperator;

use App\Models\BusinessApplication;
use App\Models\BusinessOperator;
use App\Models\BusinessProfileImage;
use App\Models\BusinessReview;
use App\Models\BusinessSetting;
use App\Models\BusinessTippingAmountSetting;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class BusinessOperatorRepository implements BusinessOperatorRepositoryInterface
{
    public function findByBusinessId(int $businessId): ?BusinessOperator
    {
        return BusinessOperator::with('businessProfileImages')->find($businessId);
    }

    public function getAllBusinessOperatorsWithImages(): Collection
    {
        return BusinessOperator::with('businessProfileImages')->get();
    }

    public function findBusinessOperatorWithStaff(int $businessId): ?BusinessOperator
    {
        return BusinessOperator::with(['staff' => function ($query) {
            $query->orderBy('points', 'desc')
                    ->orderBy('staff_name', 'asc');
        }, 'staff.staffProfileImages'])->find($businessId);
    }

    public function findBusinessOperatorWithStaffDetail(int $businessId): ?BusinessOperator
    {
        return BusinessOperator::with(['staff' => function ($query) {
            $query->orderBy('points', 'desc')
                    ->orderBy('staff_name', 'asc');
        }, 'staff.staffProfileImages', 'staff.staffFavorites', 'staff.staffSchedules'])->find($businessId);
    }

    public function findBusinessOperatorWithStaffDetailForGuest(int $businessId): ?BusinessOperator
    {
        return BusinessOperator::with(['staff' => function ($query) {
            $query->orderBy('points', 'desc')
                    ->orderBy('staff_name', 'asc');
        }, 'staff.staffProfileImages', 'staff.staffSchedules'])->find($businessId);
    }
    /**
     * 口コミ取得
     *
     * @param int $reviewId
     * @return BusinessReview
     */
    public function getReview(int $reviewId): BusinessReview
    {
        return BusinessReview::with('user')->find($reviewId);
    }
    public function getBusinessReviews(int $businessId): Collection
    {
        return BusinessOperator::with('businessReviews.user.userProfileImage')->where('business_id', $businessId)->get();
    }

    public function getAllBusinessReviews(int $businessId): BusinessOperator
    {
        return BusinessOperator::with('businessReviews')->findOrFail($businessId);
    }
    public function storeReviw(int $businessId, int $userId, string $comment): BusinessReview
    {
        return BusinessReview::create([
            'business_id' => $businessId,
            'user_id' => $userId,
            'comment' => $comment
        ]);
    }

    public function deleteReview(int $businessId, int $reviewId, int $userId): bool
    {
        // 特定のビジネスIDとレビューIDに対応するレビューを検索
        return BusinessReview::where('business_id', $businessId)
                                ->where('review_id', $reviewId)
                                ->where('user_id', $userId)
                                ->firstOrFail()->delete();
    }

    public function getBusinessTippingAmountSettingByBusinessId(int $businessId): Collection
    {
        return BusinessTippingAmountSetting::with('tippingAmountSetting')->where('business_id', $businessId)->get();
    }

    public function getBusinessSettingByBusinessId(int $businessId): BusinessSetting
    {
        return BusinessSetting::where('business_id', $businessId)
            ->first();
    }

    public function updateBusinessSettingBySettingId(int $settingId, array $param): void
    {
        $businessSetting = BusinessSetting::find($settingId);
        $businessSetting->fill($param);
        $businessSetting->save();
    }

    public function createBusinessSetting(array $param): void
    {
        BusinessSetting::create($param);
    }


    public function getBusinessTippingAmountSettingByBusinessSetting(int $businessId, int $settingId): Collection
    {
        return BusinessTippingAmountSetting::where('business_id', $businessId)
            ->where('setting_id', $settingId)
            ->get();
    }

    public function deleteBusinessTippingAmountSettingByBusiness(int $businessId): void
    {
        BusinessTippingAmountSetting::where('business_id', $businessId)
        ->delete();
    }

    public function createBusinessTippingAmountSetting(int $businessId, int $settingId): void
    {
        BusinessTippingAmountSetting::create([
            'business_id'   => $businessId,
            'setting_id'    => $settingId
        ]);
    }

    public function getAllPaginateBusiness(): LengthAwarePaginator
    {
        return BusinessOperator::with('businessSettings', 'corporation')->paginate(10);
    }

    public function searchBusinessOperatorByRequest(Request $request): LengthAwarePaginator
    {
        $query = BusinessOperator::with(['businessSettings', 'corporation']);

        if ($request->filled('businessName')) {
            $query->where('business_name', 'like', '%' . $request->businessName . '%');
        }

        if ($request->filled('corporationName')) {
            $query->where('corporation_name', 'like', '%' . $request->corporationName . '%');
        }

        if ($request->filled('businessForm')) {
            $query->where('business_form', $request->businessForm);
        }

        if ($request->filled('parentCorporationId')) {
            $query->where('corporation_id', $request->parentCorporationId);
        }

        if ($request->filled('isPublish')) {
            $query->whereHas('businessSettings', function ($query) use ($request) {
                $query->where('is_publish', $request->isPublish);
            });
        }

        if ($request->filled('businessStatus')) {
            $query->where('business_status', $request->businessStatus);
        }

        return $query->paginate(10)->appends($request->all());
    }

    public function getBusinessDetailsByBusinessId(int $businessId): BusinessOperator
    {
        return BusinessOperator::with('businessSettings', 'corporation')->find($businessId);
    }

    public function createBusinessByRequest(array $request): BusinessOperator
    {
        return BusinessOperator::create($request);
    }

    public function updateBusinessByRequest(array $request): void
    {
        $business = BusinessOperator::find($request['business_id']);
        $business->fill($request);
        $business->save();
    }

    public function createBusinessApplicationByRequest(array $request): BusinessApplication
    {
        return BusinessApplication::create($request);
    }

    public function updateBusinessApplicationByRequest(array $request): void
    {
        $businessApplication = BusinessApplication::find($request['business_application_id']);
        $businessApplication->fill($request);
        $businessApplication->save();
    }

    public function getBusinessApplicationByApplicationId(int $applicationId): BusinessApplication
    {
        return BusinessApplication::where('business_application_id', $applicationId)->first();
    }

    public function getReviewBybusinessOrderByDate(int $businessId): Collection
    {
        return BusinessReview::selectRaw("review_id, user_id, comment,to_char(created_at, 'YYYY年FMMM月FMDD日') as date")
            ->with('user.userProfileImage')
            ->where('business_id', $businessId)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function deleteReviewById(int $reviewId): void
    {
        BusinessReview::find($reviewId)->delete();
    }

    public function logicalDeleteBusinessOperatorById(int $businessId): void
    {
        BusinessOperator::find($businessId)->delete();
    }

    public function deleteImageById(int $imageId): void
    {
        BusinessProfileImage::find($imageId)->delete();
    }

    public function deleteReviewsByBusinessId(int $businessId): void
    {
        BusinessReview::where('business_id', $businessId)->delete();
    }

    public function deleteReviewsByUserId(int $userId): void
    {
        BusinessReview::where('user_id', $userId)->delete();
    }

    public function deleteSettingByBusinessId(int $businessId): void
    {
        BusinessSetting::where('business_id', $businessId)->delete();
    }

    public function findBusinessProfileImageByBusinessIdOrder(int $businessId, int $order): ?BusinessProfileImage
    {
        $where = ['business_id' => $businessId, 'order' => $order];
        return BusinessProfileImage::where($where)->first();
    }

    public function createBusinessProfileImage(int $businessId, int $order, string $fileName, string $fileType, int $fileSize): BusinessProfileImage
    {
        $create = [
            'business_id'  => $businessId,
            'order'     => $order,
            'file_name' => $fileName,
            'file_type' => $fileType,
            'file_size' => $fileSize
        ];
        return BusinessProfileImage::create($create);
    }

    public function updateBusinessProfileImage(int $businessId, int $order, string $fileName, string $fileType, int $fileSize): BusinessProfileImage
    {
        $where = ['business_id' => $businessId, 'order' => $order];
        $businessProfileImage = BusinessProfileImage::where($where)->first();
        $businessProfileImage->file_name = $fileName;
        $businessProfileImage->file_type = $fileType;
        $businessProfileImage->file_size = $fileSize;
        $businessProfileImage->save();
        return $businessProfileImage;
    }

    public function deleteBusinessProfileImageByBusinessIdOrder(int $businessId, int $order): void
    {
        $where = ['business_id' => $businessId, 'order' => $order];
        BusinessProfileImage::where($where)->delete();
    }

    public function findByEmailHash(string $emailHash): ?BusinessOperator
    {
        return BusinessOperator::where('email_hash', $emailHash)->first();
    }

    public function updateEmail(int $businessId, string $email, string $emailHash): void
    {
        $user = BusinessOperator::find($businessId);
        $user->email = $email;
        $user->email_hash = $emailHash;
        $user->save();
    }

    public function updatePassword(int $businessId, string $password): void
    {
        $user = BusinessOperator::find($businessId);
        $user->password = Hash::make($password);
        $user->save();
    }

    public function getBusinessByCorporationId(int $corporationId): Collection
    {
        return BusinessOperator::with('businessSettings')
        ->where('corporation_id', $corporationId)
        ->get();
    }

    public function getBusinessApplicationByCorporationId(int $corporationId): Collection
    {
        return BusinessApplication::with('businessOperator')
        ->where('corporation_id', $corporationId)
        ->get();
    }

    public function findBusinessApplicationByBusinessApplicationId(int $businessApplicationId): BusinessApplication
    {
        return BusinessApplication::with('businessOperator')
        ->find($businessApplicationId);
    }

    public function existsPublishByCorporationId(int $corporationId): bool
    {
        return BusinessSetting::join('business_operators', 'business_operators.business_id', 'business_settings.business_id')
        ->where('business_settings.is_publish', false)
        ->exists();
    }
}
